<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 root categories (parent_id = null)
        $rootCategories = Category::factory(5)->create();

        // Create 5 child categories with valid parent_id references
        foreach ($rootCategories->take(3) as $parent) {
            Category::factory(2)->withParent($parent->id)->create();
        }
    }
}
