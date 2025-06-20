<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    public function run(): void
    {
        Storage::factory()->count(10)->create();
    }
}
