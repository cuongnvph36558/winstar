<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderDetail;

class UpdateOrderDetailsVariantInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-order-details-variant-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thông tin variant gốc cho các đơn hàng cũ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật thông tin variant gốc cho các đơn hàng cũ...');
        
        $orderDetails = OrderDetail::with(['variant.color', 'variant.storage', 'variant.product'])
            ->whereNull('original_variant_name')
            ->whereNotNull('variant_id')
            ->get();
        
        $this->info("Tìm thấy {$orderDetails->count()} đơn hàng cần cập nhật.");
        
        $updated = 0;
        foreach ($orderDetails as $detail) {
            if ($detail->variant) {
                $originalVariantName = $detail->variant->variant_name ?? '';
                $originalColorName = $detail->variant->color ? $detail->variant->color->name : '';
                $originalStorageName = $detail->variant->storage ? $detail->variant->storage->name : '';
                $originalStorageCapacity = $detail->variant->storage ? $detail->variant->storage->capacity : '';
                
                $detail->update([
                    'original_variant_name' => $originalVariantName,
                    'original_color_name' => $originalColorName,
                    'original_storage_name' => $originalStorageName,
                    'original_storage_capacity' => $originalStorageCapacity,
                ]);
                
                $updated++;
                $this->line("Đã cập nhật đơn hàng #{$detail->order_id} - Sản phẩm: {$detail->product_name}");
            }
        }
        
        $this->info("Hoàn thành! Đã cập nhật {$updated} đơn hàng.");
    }
}
