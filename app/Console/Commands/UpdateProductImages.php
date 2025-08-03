<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateProductImages extends Command
{
    protected $signature = 'products:update-images';
    protected $description = 'Update product images with sample images from storage';

    public function handle()
    {
        $this->info('Updating product images...');
        
        // Get available images from storage
        $images = [];
        $files = Storage::disk('public')->files('products');
        
        foreach ($files as $file) {
            if (str_ends_with($file, '.jpg') || str_ends_with($file, '.png')) {
                $images[] = $file;
            }
        }
        
        if (empty($images)) {
            $this->error('No images found in storage/app/public/products/');
            return 1;
        }
        
        $this->info('Found ' . count($images) . ' images');
        
        // Get products without images or with missing images
        $products = Product::whereNull('image')
            ->orWhere('image', '')
            ->limit(count($images))
            ->get();
            
        if ($products->isEmpty()) {
            // If no products without images, update existing ones
            $products = Product::limit(count($images))->get();
        }
        
        $updated = 0;
        foreach ($products as $index => $product) {
            if (isset($images[$index])) {
                $product->update([
                    'image' => $images[$index]
                ]);
                $updated++;
                $this->line("Updated product: {$product->name} -> {$images[$index]}");
            }
        }
        
        $this->info("Successfully updated {$updated} products with images");
        return 0;
    }
} 