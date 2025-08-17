<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Kiểm tra stock có đủ không
     */
    public function checkStock($productId, $variantId = null, $quantity = 1, $useLock = false): array
    {
        $query = $useLock ? Product::lockForUpdate() : Product::query();
        $product = $query->find($productId);
        
        if (!$product) {
            return [
                'available' => false,
                'message' => 'Sản phẩm không tồn tại',
                'current_stock' => 0
            ];
        }

        $variant = null;
        if ($variantId) {
            $variantQuery = $useLock ? ProductVariant::lockForUpdate() : ProductVariant::query();
            $variant = $variantQuery->where('id', $variantId)
                ->where('product_id', $productId)
                ->first();
            
            if (!$variant) {
                return [
                    'available' => false,
                    'message' => 'Phiên bản sản phẩm không tồn tại',
                    'current_stock' => 0
                ];
            }
        }

        $currentStock = $variant ? $variant->stock_quantity : $product->stock_quantity;

        if ($currentStock < $quantity) {
            return [
                'available' => false,
                'message' => $currentStock > 0 
                    ? "Sản phẩm vừa có người đặt trước. Chỉ còn {$currentStock} trong kho" 
                    : "Sản phẩm đã hết hàng",
                'current_stock' => $currentStock,
                'requested_quantity' => $quantity,
                'is_out_of_stock' => $currentStock == 0
            ];
        }

        return [
            'available' => true,
            'current_stock' => $currentStock,
            'remaining_after_purchase' => $currentStock - $quantity
        ];
    }

    /**
     * Trừ stock
     */
    public function decrementStock($productId, $variantId = null, $quantity = 1): bool
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception('Sản phẩm không tồn tại');
            }

            if ($variantId) {
                $variant = ProductVariant::where('id', $variantId)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();
                
                if (!$variant) {
                    throw new \Exception('Phiên bản sản phẩm không tồn tại');
                }

                if ($variant->stock_quantity < $quantity) {
                    throw new \Exception("Không đủ stock: cần {$quantity}, có {$variant->stock_quantity}");
                }

                $variant->stock_quantity = $variant->stock_quantity - $quantity;
                $variant->save();
            } else {
                if ($product->stock_quantity < $quantity) {
                    throw new \Exception("Không đủ stock: cần {$quantity}, có {$product->stock_quantity}");
                }

                $product->stock_quantity = $product->stock_quantity - $quantity;
                $product->save();
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock decrement failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            return false;
        }
    }

    /**
     * Hoàn trả stock
     */
    public function incrementStock($productId, $variantId = null, $quantity = 1): bool
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception('Sản phẩm không tồn tại');
            }

            if ($variantId) {
                $variant = ProductVariant::where('id', $variantId)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();
                
                if (!$variant) {
                    throw new \Exception('Phiên bản sản phẩm không tồn tại');
                }

                $variant->stock_quantity = $variant->stock_quantity + $quantity;
                $variant->save();
            } else {
                $product->stock_quantity = $product->stock_quantity + $quantity;
                $product->save();
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock increment failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            return false;
        }
    }

    /**
     * Lấy tổng stock của sản phẩm (bao gồm tất cả variants)
     */
    public function getTotalStock($productId): int
    {
        $product = Product::find($productId);
        if (!$product) {
            return 0;
        }

        $productStock = $product->stock_quantity ?? 0;
        $variantsStock = $product->variants()->sum('stock_quantity') ?? 0;

        return $productStock + $variantsStock;
    }

    /**
     * Kiểm tra sản phẩm có còn hàng không
     */
    public function hasStock($productId, $variantId = null): bool
    {
        $check = $this->checkStock($productId, $variantId, 1);
        return $check['available'];
    }

    /**
     * Trừ stock an toàn với kiểm tra và lock
     */
    public function safeDecrementStock($productId, $variantId = null, $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            // Kiểm tra stock với lock
            $stockCheck = $this->checkStock($productId, $variantId, $quantity, true);
            
            if (!$stockCheck['available']) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $stockCheck['message'],
                    'current_stock' => $stockCheck['current_stock']
                ];
            }

            // Trừ stock với kiểm tra lại sau khi lock
            if ($variantId) {
                $variant = ProductVariant::lockForUpdate()
                    ->where('id', $variantId)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$variant) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Phiên bản sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra lại stock sau khi lock
                if ($variant->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Sản phẩm vừa có người đặt trước. Chỉ còn {$variant->stock_quantity} trong kho",
                        'current_stock' => $variant->stock_quantity
                    ];
                }

                $variant->stock_quantity = $variant->stock_quantity - $quantity;
                $variant->save();
                $finalStock = $variant->stock_quantity;
            } else {
                $product = Product::lockForUpdate()->find($productId);
                if (!$product) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra lại stock sau khi lock
                if ($product->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Sản phẩm vừa có người đặt trước. Chỉ còn {$product->stock_quantity} trong kho",
                        'current_stock' => $product->stock_quantity
                    ];
                }

                $product->stock_quantity = $product->stock_quantity - $quantity;
                $product->save();
                $finalStock = $product->stock_quantity;
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Trừ stock thành công',
                'current_stock' => $finalStock,
                'decremented_quantity' => $quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Safe stock decrement failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi trừ stock: ' . $e->getMessage(),
                'current_stock' => 0
            ];
        }
    }

    /**
     * Kiểm tra và trừ kho an toàn khi chuyển sang trạng thái delivered
     * Sử dụng transaction và lock để tránh race condition
     */
    public function safeDecrementStockForDelivery($productId, $variantId = null, $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            // Lock và kiểm tra stock
            if ($variantId) {
                $variant = ProductVariant::lockForUpdate()
                    ->where('id', $variantId)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$variant) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Phiên bản sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra stock có đủ không
                if ($variant->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Không đủ stock để giao hàng. Cần {$quantity}, có {$variant->stock_quantity}",
                        'current_stock' => $variant->stock_quantity,
                        'requested_quantity' => $quantity
                    ];
                }

                // Trừ stock
                $variant->stock_quantity = $variant->stock_quantity - $quantity;
                $variant->save();
                $finalStock = $variant->stock_quantity;
            } else {
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra stock có đủ không
                if ($product->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Không đủ stock để giao hàng. Cần {$quantity}, có {$product->stock_quantity}",
                        'current_stock' => $product->stock_quantity,
                        'requested_quantity' => $quantity
                    ];
                }

                // Trừ stock
                $product->stock_quantity = $product->stock_quantity - $quantity;
                $product->save();
                $finalStock = $product->stock_quantity;
            }

            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Đã trừ kho thành công',
                'current_stock' => $finalStock,
                'decremented_quantity' => $quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Safe decrement stock for delivery failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi trừ kho: ' . $e->getMessage(),
                'current_stock' => 0
            ];
        }
    }

    /**
     * Đặt trước kho khi tạo đơn hàng
     * Sử dụng transaction và lock để tránh race condition
     */
    public function reserveStock($productId, $variantId = null, $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            // Lock và kiểm tra stock
            if ($variantId) {
                $variant = ProductVariant::lockForUpdate()
                    ->where('id', $variantId)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$variant) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Phiên bản sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra stock có đủ không
                if ($variant->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Sản phẩm vừa có người đặt trước. Chỉ còn {$variant->stock_quantity} trong kho",
                        'current_stock' => $variant->stock_quantity,
                        'requested_quantity' => $quantity
                    ];
                }

                // Trừ stock để đặt trước
                $variant->stock_quantity = $variant->stock_quantity - $quantity;
                $variant->save();
                $finalStock = $variant->stock_quantity;
            } else {
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại',
                        'current_stock' => 0
                    ];
                }

                // Kiểm tra stock có đủ không
                if ($product->stock_quantity < $quantity) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Sản phẩm vừa có người đặt trước. Chỉ còn {$product->stock_quantity} trong kho",
                        'current_stock' => $product->stock_quantity,
                        'requested_quantity' => $quantity
                    ];
                }

                // Trừ stock để đặt trước
                $product->stock_quantity = $product->stock_quantity - $quantity;
                $product->save();
                $finalStock = $product->stock_quantity;
            }

            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Đã đặt trước kho thành công',
                'current_stock' => $finalStock,
                'reserved_quantity' => $quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reserve stock failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi đặt trước kho: ' . $e->getMessage(),
                'current_stock' => 0
            ];
        }
    }

    /**
     * Hoàn lại kho đã đặt trước khi hủy đơn hàng
     */
    public function releaseReservedStock($productId, $variantId = null, $quantity = 1): array
    {
        try {
            DB::beginTransaction();

            if ($variantId) {
                $variant = ProductVariant::lockForUpdate()
                    ->where('id', $variantId)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$variant) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Phiên bản sản phẩm không tồn tại'
                    ];
                }

                // Hoàn lại stock
                $variant->stock_quantity = $variant->stock_quantity + $quantity;
                $variant->save();
                $finalStock = $variant->stock_quantity;
            } else {
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại'
                    ];
                }

                // Hoàn lại stock
                $product->stock_quantity = $product->stock_quantity + $quantity;
                $product->save();
                $finalStock = $product->stock_quantity;
            }

            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Đã hoàn lại kho đặt trước thành công',
                'current_stock' => $finalStock,
                'released_quantity' => $quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Release reserved stock failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi hoàn lại kho: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra stock có sẵn cho đặt hàng (bao gồm cả kho đã đặt trước)
     */
    public function checkAvailableStock($productId, $variantId = null, $quantity = 1): array
    {
        $query = Product::query();
        $product = $query->find($productId);
        
        if (!$product) {
            return [
                'available' => false,
                'message' => 'Sản phẩm không tồn tại',
                'current_stock' => 0
            ];
        }

        $variant = null;
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->first();
            
            if (!$variant) {
                return [
                    'available' => false,
                    'message' => 'Phiên bản sản phẩm không tồn tại',
                    'current_stock' => 0
                ];
            }
        }

        $currentStock = $variant ? $variant->stock_quantity : $product->stock_quantity;

        if ($currentStock < $quantity) {
            return [
                'available' => false,
                'message' => $currentStock > 0 
                    ? "Sản phẩm vừa có người đặt trước. Chỉ còn {$currentStock} trong kho" 
                    : "Sản phẩm đã hết hàng",
                'current_stock' => $currentStock,
                'requested_quantity' => $quantity,
                'is_out_of_stock' => $currentStock == 0
            ];
        }

        return [
            'available' => true,
            'current_stock' => $currentStock,
            'remaining_after_purchase' => $currentStock - $quantity
        ];
    }
} 