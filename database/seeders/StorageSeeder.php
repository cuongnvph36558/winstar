<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    public function run(): void
    {
        $storages = [
            ['capacity' => '32GB'],
            ['capacity' => '64GB'],
            ['capacity' => '128GB'],
            ['capacity' => '256GB'],
            ['capacity' => '512GB'],
            ['capacity' => '1TB'],
            ['capacity' => '2TB'],
        ];

        foreach ($storages as $storage) {
            Storage::create($storage);
        }
    }
}
