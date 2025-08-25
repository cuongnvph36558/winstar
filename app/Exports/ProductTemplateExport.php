<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['iPhone 15 Pro', 'Điện thoại', 'iPhone 15 Pro mới nhất từ Apple với chip A17 Pro', 'active'],
            ['Samsung Galaxy S24', 'Điện thoại', 'Samsung Galaxy S24 Ultra với camera 200MP', 'active'],
            ['MacBook Pro M3', 'Laptop', 'MacBook Pro với chip M3 mạnh mẽ', 'active'],
            ['iPad Pro 12.9', 'Máy tính bảng', 'iPad Pro 12.9 inch với chip M2', 'active'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'category_name', 
            'description',
            'status'
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
