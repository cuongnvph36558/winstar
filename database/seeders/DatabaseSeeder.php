<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // Basic data first
            AuthorSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            StorageSeeder::class,
            
            // Users and permissions
            UserSeeder::class,
            RolePermissionSeeder::class,
            
            // Products and related data
            ProductSeeder::class,
            ProductVariantSeeder::class,
            
            // Content and features
            PostSeeder::class,
            FeatureSeeder::class,
            FeatureItemSeeder::class,
            ServiceSeeder::class,
            
            // Banners
            BannerSeeder::class,
            
            // Reviews and comments
            ReviewSeeder::class,
            
            // Points and vouchers
            PointVoucherSeeder::class,
            
            // Coupons and attendance
            CouponSeeder::class,
            CouponUserSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
