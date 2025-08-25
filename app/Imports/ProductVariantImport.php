<?php

namespace App\Imports;

use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Color;
use App\Models\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class ProductVariantImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public function model(array $row)
    {
        try {
            // Tìm product theo tên
            $product = Product::where('name', trim($row['product_name']))->first();
            
            if (!$product) {
                Log::warning("Product not found: " . $row['product_name']);
                return null;
            }

            // Tìm color theo tên (nếu có)
            $colorId = null;
            if (!empty($row['color_name'])) {
                $color = Color::where('name', trim($row['color_name']))->first();
                if ($color) {
                    $colorId = $color->id;
                } else {
                    Log::warning("Color not found: " . $row['color_name']);
                }
            }

            // Tìm storage theo capacity (nếu có)
            $storageId = null;
            if (!empty($row['storage_capacity'])) {
                $storage = Storage::where('capacity', trim($row['storage_capacity']))->first();
                if ($storage) {
                    $storageId = $storage->id;
                } else {
                    Log::warning("Storage not found: " . $row['storage_capacity']);
                }
            }

            // Xử lý giá khuyến mãi
            $promotionPrice = null;
            if (!empty($row['promotion_price']) && is_numeric($row['promotion_price'])) {
                $promotionPrice = (float) $row['promotion_price'];
                if ($promotionPrice >= (float) $row['price']) {
                    Log::warning("Promotion price must be less than regular price for product: " . $row['product_name']);
                    $promotionPrice = null;
                }
            }

            return new ProductVariant([
                'product_id' => $product->id,
                'price' => (float) $row['price'],
                'promotion_price' => $promotionPrice,
                'stock_quantity' => (int) $row['stock_quantity'],
                'color_id' => $colorId,
                'storage_id' => $storageId,
                'image_variant' => json_encode([]), // Mặc định không có ảnh, có thể upload sau
            ]);
        } catch (\Exception $e) {
            Log::error("Error importing product variant: " . $e->getMessage(), $row);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'promotion_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'color_name' => 'nullable|string|max:255',
            'storage_capacity' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'promotion_price.numeric' => 'Giá khuyến mãi phải là số',
            'promotion_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0',
            'stock_quantity.required' => 'Số lượng không được để trống',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên',
            'stock_quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
