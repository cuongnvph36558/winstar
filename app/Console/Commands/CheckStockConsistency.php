<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckStockConsistency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-consistency {--fix : Tự động sửa lỗi nếu tìm thấy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra tính nhất quán của kho hàng với logic mới';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Bắt đầu kiểm tra tính nhất quán kho hàng...');
        
        $fixMode = $this->option('fix');
        
        if ($fixMode) {
            $this->warn('⚠️ Chế độ sửa lỗi đã được bật!');
        }

        $issues = [];
        
        // Kiểm tra 1: Stock âm
        $this->info('📦 Kiểm tra stock âm...');
        $negativeStockProducts = Product::where('stock_quantity', '<', 0)->get();
        $negativeStockVariants = ProductVariant::where('stock_quantity', '<', 0)->get();
        
        if ($negativeStockProducts->count() > 0 || $negativeStockVariants->count() > 0) {
            $issues[] = 'Tìm thấy stock âm';
            
            if ($fixMode) {
                $this->info('🔧 Sửa stock âm...');
                Product::where('stock_quantity', '<', 0)->update(['stock_quantity' => 0]);
                ProductVariant::where('stock_quantity', '<', 0)->update(['stock_quantity' => 0]);
                $this->info('✅ Đã sửa stock âm');
            } else {
                $this->warn("   - Sản phẩm có stock âm: {$negativeStockProducts->count()}");
                $this->warn("   - Variant có stock âm: {$negativeStockVariants->count()}");
            }
        } else {
            $this->info('✅ Không có stock âm');
        }

        // Kiểm tra 2: Đơn hàng đã giao nhưng chưa trừ kho
        $this->info('📋 Kiểm tra đơn hàng đã giao...');
        
        $deliveredOrders = Order::whereIn('status', ['delivered', 'received', 'completed'])
            ->with('orderDetails')
            ->get();
            
        $inconsistentOrders = [];
        
        foreach ($deliveredOrders as $order) {
            foreach ($order->orderDetails as $detail) {
                if ($detail->variant_id) {
                    $variant = ProductVariant::find($detail->variant_id);
                    if ($variant && $variant->stock_quantity < 0) {
                        $inconsistentOrders[] = [
                            'order_id' => $order->id,
                            'order_code' => $order->code_order,
                            'product_name' => $detail->product_name,
                            'variant_id' => $detail->variant_id,
                            'quantity' => $detail->quantity,
                            'current_stock' => $variant->stock_quantity
                        ];
                    }
                } else {
                    $product = Product::find($detail->product_id);
                    if ($product && $product->stock_quantity < 0) {
                        $inconsistentOrders[] = [
                            'order_id' => $order->id,
                            'order_code' => $order->code_order,
                            'product_name' => $detail->product_name,
                            'product_id' => $detail->product_id,
                            'quantity' => $detail->quantity,
                            'current_stock' => $product->stock_quantity
                        ];
                    }
                }
            }
        }
        
        if (count($inconsistentOrders) > 0) {
            $issues[] = 'Tìm thấy đơn hàng đã giao nhưng stock không nhất quán';
            
            if ($fixMode) {
                $this->info('🔧 Sửa stock cho đơn hàng đã giao...');
                foreach ($inconsistentOrders as $issue) {
                    if (isset($issue['variant_id'])) {
                        ProductVariant::where('id', $issue['variant_id'])
                            ->update(['stock_quantity' => 0]);
                    } else {
                        Product::where('id', $issue['product_id'])
                            ->update(['stock_quantity' => 0]);
                    }
                }
                $this->info('✅ Đã sửa stock cho đơn hàng đã giao');
            } else {
                $this->warn("   - Tìm thấy " . count($inconsistentOrders) . " sản phẩm có stock không nhất quán");
                foreach ($inconsistentOrders as $issue) {
                    $this->warn("     * Đơn hàng #{$issue['order_code']} - {$issue['product_name']}: stock hiện tại {$issue['current_stock']}");
                }
            }
        } else {
            $this->info('✅ Tất cả đơn hàng đã giao đều có stock nhất quán');
        }

        // Tóm tắt
        $this->newLine();
        $this->info('📊 Tóm tắt kiểm tra:');
        
        if (empty($issues)) {
            $this->info('✅ Không tìm thấy vấn đề nào với kho hàng');
        } else {
            $this->warn('⚠️ Tìm thấy ' . count($issues) . ' vấn đề:');
            foreach ($issues as $issue) {
                $this->warn("   - {$issue}");
            }
            
            if (!$fixMode) {
                $this->info('💡 Chạy lệnh với --fix để tự động sửa lỗi');
            }
        }

        return 0;
    }
}
