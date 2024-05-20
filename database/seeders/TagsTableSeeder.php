<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Tag::truncate();
        $tags = [
            'عمومی',
            'خبری',
            'ورزشی',
            'فیلم',
            'طنز',
            'بازی_و_سرگرمی'
        ];

        foreach ($tags as $tag) {
            Tag::factory()->create([
                'title' => $tag
            ]);
        }

        $this->command->info('tags seeded successfully');
    }
}
