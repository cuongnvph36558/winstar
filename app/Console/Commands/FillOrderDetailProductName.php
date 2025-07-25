<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderDetail;
use App\Models\Product;

class FillOrderDetailProductName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-details:fill-product-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill product_name for order_details where it is null, using current product name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 0;
        $details = OrderDetail::whereNull('product_name')->get();
        foreach ($details as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $detail->product_name = $product->name;
                $detail->save();
                $count++;
            }
        }
        $this->info("Đã cập nhật $count order_details.");
    }
}
