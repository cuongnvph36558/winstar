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
            AuthorSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            StorageSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            RolePermissionSeeder::class,
            UserTestSeeder::class,
            PostSeeder::class,
            FeatureSeeder::class,
            FeatureItemSeeder::class,
        ]);
    }
}
