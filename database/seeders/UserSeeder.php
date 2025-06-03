<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\User;

class UserSeeder extends Seeder{
    public function run(): void {
        User::factory()->count(2)->create()
    }
}