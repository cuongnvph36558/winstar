<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class DownloadProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download sample product images for all phone products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to download product images...');

        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/products');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Sample image URLs for different phone brands
        $sampleImages = [
            // Apple
            'iphone-15-pro-max.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'iphone-15-pro.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'iphone-15.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Samsung
            'galaxy-s24-ultra.jpg' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&h=800&fit=crop',
            'galaxy-s24-plus.jpg' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&h=800&fit=crop',
            'galaxy-a55.jpg' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&h=800&fit=crop',
            
            // Xiaomi
            'xiaomi-14-ultra.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'xiaomi-14.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'redmi-note-13-pro-plus.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // OPPO
            'oppo-find-x7-ultra.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'oppo-find-x7.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'oppo-reno-11.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Vivo
            'vivo-x100-pro.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'vivo-x100.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'vivo-v29.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Realme
            'realme-gt-neo-5-se.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'realme-11-pro-plus.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // OnePlus
            'oneplus-12.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'oneplus-12r.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Huawei
            'huawei-pura-70-ultra.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            'huawei-nova-12.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Nokia
            'nokia-g60.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // Motorola
            'motorola-edge-40.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
            
            // ASUS
            'asus-rog-phone-8.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop',
        ];

        $downloadedCount = 0;
        $failedCount = 0;

        foreach ($sampleImages as $filename => $url) {
            try {
                $this->info("Downloading {$filename}...");
                
                $response = Http::timeout(30)->get($url);
                
                if ($response->successful()) {
                    $filePath = $storagePath . '/' . $filename;
                    file_put_contents($filePath, $response->body());
                    
                    // Create variant images
                    $this->createVariantImages($storagePath, $filename);
                    
                    $downloadedCount++;
                    $this->info("✓ Downloaded {$filename}");
                } else {
                    $this->error("✗ Failed to download {$filename}");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Error downloading {$filename}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $this->info("\nDownload completed!");
        $this->info("Successfully downloaded: {$downloadedCount} images");
        $this->info("Failed downloads: {$failedCount} images");
        $this->info("Images saved to: {$storagePath}");
        
        // Create symbolic link if it doesn't exist
        $this->createStorageLink();
    }

    private function createVariantImages($storagePath, $filename)
    {
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Create variant images by copying the main image
        for ($i = 1; $i <= 2; $i++) {
            $variantFilename = $baseName . '-variant' . $i . '.' . $extension;
            $sourcePath = $storagePath . '/' . $filename;
            $targetPath = $storagePath . '/' . $variantFilename;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $targetPath);
            }
        }
    }

    private function createStorageLink()
    {
        $this->info("Creating storage link...");
        
        try {
            $target = public_path('storage');
            $link = storage_path('app/public');
            
            if (!file_exists($target)) {
                symlink($link, $target);
                $this->info("✓ Storage link created successfully");
            } else {
                $this->info("✓ Storage link already exists");
            }
        } catch (\Exception $e) {
            $this->error("✗ Failed to create storage link: " . $e->getMessage());
        }
    }
}
