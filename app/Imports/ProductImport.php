<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public function model(array $row)
    {
        try {
            // Tìm category theo tên
            $category = Category::where('name', trim($row['category_name']))->first();
            
            if (!$category) {
                Log::warning("Category not found: " . $row['category_name']);
                return null;
            }

            return new Product([
                'name' => trim($row['name']),
                'description' => trim($row['description'] ?? ''),
                'category_id' => $category->id,
                'status' => $this->parseStatus($row['status'] ?? 'active'),
                'price' => 0, // Giá mặc định là 0, sẽ được set ở biến thể
                'stock_quantity' => 0, // Số lượng mặc định là 0, sẽ được set ở biến thể
                'view' => 0,
            ]);
        } catch (\Exception $e) {
            Log::error("Error importing product: " . $e->getMessage(), $row);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,1,0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'category_name.required' => 'Tên danh mục không được để trống',
            'status.in' => 'Trạng thái phải là active/inactive hoặc 1/0',
        ];
    }

    private function parseStatus($status)
    {
        if (is_null($status)) return 1;
        
        $status = strtolower(trim($status));
        
        if (in_array($status, ['active', '1', 'true', 'yes'])) {
            return 1;
        }
        
        if (in_array($status, ['inactive', '0', 'false', 'no'])) {
            return 0;
        }
        
        return 1; // Mặc định là active
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
