<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadBannerImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:banner-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download banner images for phone website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to download banner images...');

        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/banners');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Banner image URLs for phone website
        $bannerImages = [
            [
                'filename' => 'banner-1-iphone.jpg',
                'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=1200&h=400&fit=crop',
                'title' => 'iPhone 15 Pro Max - Đẳng cấp mới',
                'description' => 'Khám phá iPhone 15 Pro Max với chip A17 Pro mạnh mẽ và camera 48MP chuyên nghiệp'
            ],
            [
                'filename' => 'banner-2-samsung.jpg',
                'url' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=1200&h=400&fit=crop',
                'title' => 'Samsung Galaxy S24 Ultra - Sáng tạo vô tận',
                'description' => 'Trải nghiệm Galaxy S24 Ultra với camera 200MP và AI thông minh'
            ],
            [
                'filename' => 'banner-3-xiaomi.jpg',
                'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=1200&h=400&fit=crop',
                'title' => 'Xiaomi 14 Ultra - Hiệu năng đỉnh cao',
                'description' => 'Xiaomi 14 Ultra với chip Snapdragon 8 Gen 3 và camera Leica chuyên nghiệp'
            ],
            [
                'filename' => 'banner-4-oppo.jpg',
                'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=1200&h=400&fit=crop',
                'title' => 'OPPO Find X7 Ultra - Nghệ thuật nhiếp ảnh',
                'description' => 'OPPO Find X7 Ultra với camera Hasselblad và thiết kế độc đáo'
            ]
        ];

        $downloadedCount = 0;
        $failedCount = 0;

        foreach ($bannerImages as $banner) {
            try {
                $this->info("Downloading {$banner['filename']}...");
                
                $response = Http::timeout(30)->get($banner['url']);
                
                if ($response->successful()) {
                    $filePath = $storagePath . '/' . $banner['filename'];
                    file_put_contents($filePath, $response->body());
                    
                    $downloadedCount++;
                    $this->info("✓ Downloaded {$banner['filename']}");
                    $this->info("  Title: {$banner['title']}");
                    $this->info("  Description: {$banner['description']}");
                } else {
                    $this->error("✗ Failed to download {$banner['filename']}");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Error downloading {$banner['filename']}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $this->info("\nDownload completed!");
        $this->info("Successfully downloaded: {$downloadedCount} banner images");
        $this->info("Failed downloads: {$failedCount} banner images");
        $this->info("Banner images saved to: {$storagePath}");
        
        // Create banner data for database
        $this->createBannerData($bannerImages);
    }

    private function createBannerData($bannerImages)
    {
        $this->info("\nCreating banner data for database...");
        
        // Check if Banner model exists
        if (!class_exists('App\Models\Banner')) {
            $this->warn("Banner model not found. Skipping database creation.");
            return;
        }

        $bannerModel = new \App\Models\Banner();
        
        foreach ($bannerImages as $index => $banner) {
            try {
                // Check if banner already exists
                $existingBanner = $bannerModel->where('image_url', 'banners/' . $banner['filename'])->first();
                
                if (!$existingBanner) {
                    $bannerModel->create([
                        'title' => $banner['title'],
                        'image_url' => 'banners/' . $banner['filename'],
                        'link' => '#',
                        'start_date' => now(),
                        'end_date' => now()->addMonths(6),
                        'status' => '1'
                    ]);
                    
                    $this->info("✓ Created banner record for {$banner['filename']}");
                } else {
                    $this->info("✓ Banner record already exists for {$banner['filename']}");
                }
            } catch (\Exception $e) {
                $this->error("✗ Error creating banner record for {$banner['filename']}: " . $e->getMessage());
            }
        }
        
        $this->info("Banner data creation completed!");
    }
}
