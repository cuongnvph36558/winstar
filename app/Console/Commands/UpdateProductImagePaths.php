<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateProductImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product-image-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product image paths to use the downloaded images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating product image paths...');

        $products = Product::all();
        $updatedCount = 0;

        foreach ($products as $product) {
            $currentImage = $product->image;
            
            // Remove any existing storage prefix
            $filename = str_replace('storage/products/', '', $currentImage);
            $filename = str_replace('storage/storage/products/', '', $filename);
            
            // Check if the image file exists in storage
            $storagePath = storage_path('app/public/products/' . $filename);
            
            if (file_exists($storagePath)) {
                // Update to use correct path without storage prefix
                $product->image = 'products/' . $filename;
                $product->save();
                $updatedCount++;
                $this->info("✓ Updated {$product->name}: {$product->image}");
            } else {
                $this->warn("⚠ Image not found for {$product->name}: {$filename}");
            }
        }

        $this->info("\nUpdate completed!");
        $this->info("Updated {$updatedCount} products");
        
        // Also update product variants
        $this->updateProductVariants();
    }

    private function updateProductVariants()
    {
        $this->info("\nUpdating product variant images...");
        
        $variants = \App\Models\ProductVariant::all();
        $updatedCount = 0;

        foreach ($variants as $variant) {
            $images = json_decode($variant->image_variant, true);
            $updatedImages = [];

            foreach ($images as $image) {
                // Remove any existing storage prefix
                $filename = str_replace('storage/products/', '', $image);
                $filename = str_replace('storage/storage/products/', '', $filename);
                
                // Check if the image file exists
                $storagePath = storage_path('app/public/products/' . $filename);
                
                if (file_exists($storagePath)) {
                    $updatedImages[] = 'products/' . $filename;
                } else {
                    $updatedImages[] = $image; // Keep original if not found
                }
            }

            $variant->image_variant = json_encode($updatedImages);
            $variant->save();
            $updatedCount++;
        }

        $this->info("Updated {$updatedCount} product variants");
    }
} 