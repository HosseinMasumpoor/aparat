<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();

        $categories = [
            [
                'title' => 'عمومی',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'خبری',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'ورزشی',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'بازی و سرگرمی',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'فیلم',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'طنز',
                'icon' => null,
                'banner' => null,
            ],
            [
                'title' => 'دسته بندی کانال',
                'icon' => null,
                'banner' => null,
                'user_id' => 8
            ],
            [
                'title' => 'دسته بندی کانال 2',
                'icon' => null,
                'banner' => null,
                'user_id' => 1
            ],

        ];

        foreach ($categories as $category) {
            Category::factory()->create($category);
        }
    }
}
