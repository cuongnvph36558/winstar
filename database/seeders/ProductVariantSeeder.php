<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Storage;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Get common colors and storages
        $colors = Color::all();
        $storages = Storage::all();

        // Create variants for each product
        Product::all()->each(function ($product) use ($colors, $storages) {
            // Get 2-3 random colors for each product
            $productColors = $colors->random(rand(2, 3));
            
            // Get 2-3 random storages for each product
            $productStorages = $storages->random(rand(2, 3));

            foreach ($productColors as $color) {
                foreach ($productStorages as $storage) {
                    // Create variant name based on color and storage
                    $variantName = $color->name . ' - ' . $storage->capacity;
                    
                    // Adjust price based on storage
                    $basePrice = $product->price;
                    $basePromoPrice = $product->promotion_price;
                    
                    // Price adjustment based on storage
                    $priceMultiplier = 1.0;
                    if ($storage->capacity === '256GB') {
                        $priceMultiplier = 1.1;
                    } elseif ($storage->capacity === '512GB') {
                        $priceMultiplier = 1.2;
                    } elseif ($storage->capacity === '1TB') {
                        $priceMultiplier = 1.3;
                    } elseif ($storage->capacity === '2TB') {
                        $priceMultiplier = 1.4;
                    }

                    $variantPrice = round($basePrice * $priceMultiplier);
                    $variantPromoPrice = round($basePromoPrice * $priceMultiplier);

                    // Create sample images for the variant
                    $variantImages = [
                        $product->image,
                        str_replace('.jpg', '-variant1.jpg', $product->image),
                        str_replace('.jpg', '-variant2.jpg', $product->image)
                    ];

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variantName,
                        'image_variant' => json_encode($variantImages),
                        'price' => $variantPrice,
                        'promotion_price' => $variantPromoPrice,
                        'stock_quantity' => rand(5, 20),
                        'color_id' => $color->id,
                        'storage_id' => $storage->id,
                    ]);
                }
            }
        });
    }
}
