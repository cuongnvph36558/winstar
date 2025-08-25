<?php

namespace App\Exports;

use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductVariantExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return ProductVariant::with(['product', 'color', 'storage'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên sản phẩm',
            'Giá',
            'Giá khuyến mãi',
            'Số lượng tồn kho',
            'Màu sắc',
            'Dung lượng',
            'Ngày tạo',
            'Ngày cập nhật'
        ];
    }

    public function map($variant): array
    {
        return [
            $variant->id,
            $variant->product ? $variant->product->name : 'N/A',
            number_format($variant->price, 0, ',', '.') . ' VNĐ',
            $variant->promotion_price ? number_format($variant->promotion_price, 0, ',', '.') . ' VNĐ' : 'N/A',
            $variant->stock_quantity,
            $variant->color ? $variant->color->name : 'N/A',
            $variant->storage ? $variant->storage->capacity : 'N/A',
            $variant->created_at->format('d/m/Y H:i:s'),
            $variant->updated_at->format('d/m/Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }
}
