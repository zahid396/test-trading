<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Courses', 'description' => 'Online courses and video tutorials to learn new skills.'],
            ['name' => 'eBooks', 'description' => 'Digital books and guides on various topics.'],
            ['name' => 'Templates', 'description' => 'Ready-to-use templates for documents, designs, and more.'],
            ['name' => 'Software', 'description' => 'Desktop and web-based software applications.'],
            ['name' => 'Tools', 'description' => 'Digital tools and utilities for productivity.'],
            ['name' => 'Bundles', 'description' => 'Curated collections of products sold together at a discount.'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
