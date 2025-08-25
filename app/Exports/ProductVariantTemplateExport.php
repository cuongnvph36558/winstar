<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductVariantTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['iPhone 15 Pro', '25000000', '23000000', '50', 'Titan tự nhiên', '256GB'],
            ['iPhone 15 Pro', '28000000', '26000000', '30', 'Titan tự nhiên', '512GB'],
            ['iPhone 15 Pro', '32000000', '30000000', '20', 'Titan xanh', '1TB'],
            ['Samsung Galaxy S24', '22000000', '20000000', '40', 'Titan Gray', '256GB'],
            ['Samsung Galaxy S24', '25000000', '23000000', '25', 'Titan Gray', '512GB'],
        ];
    }

    public function headings(): array
    {
        return [
            'product_name',
            'price',
            'promotion_price',
            'stock_quantity',
            'color_name',
            'storage_capacity'
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
