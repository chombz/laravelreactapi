<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'All kinds of electronic gadgets.',
            'meta_title' => 'Electronics',
            'meta_keyword' => 'gadgets, phones, laptops',
            'meta_descrip' => 'Browse our wide range of electronics.',
            'status' => 0,
        ]);

        Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Latest trends in fashion for all.',
            'meta_title' => 'Fashion',
            'meta_keyword' => 'clothing, apparel, style',
            'meta_descrip' => 'Discover the latest fashion trends.',
            'status' => 0,
        ]);

        Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'A wide collection of books.',
            'meta_title' => 'Books',
            'meta_keyword' => 'novels, fiction, non-fiction',
            'meta_descrip' => 'Find your next favorite book here.',
            'status' => 0,
        ]);
    }
}
