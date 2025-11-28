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
            [
                'name' => 'Graphics & Design',
                'slug' => 'graphics-design',
                'description' => 'Logo design, branding, illustrations, and graphic design services',
            ],
            [
                'name' => 'Programming & Tech',
                'slug' => 'programming-tech',
                'description' => 'Web development, mobile apps, software development, and technical services',
            ],
            [
                'name' => 'Writing & Translation',
                'slug' => 'writing-translation',
                'description' => 'Content writing, copywriting, translation, and proofreading services',
            ],
            [
                'name' => 'Video & Animation',
                'slug' => 'video-animation',
                'description' => 'Video editing, motion graphics, 3D animation, and video production',
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'description' => 'SEO, social media marketing, email marketing, and advertising services',
            ],
            [
                'name' => 'Music & Audio',
                'slug' => 'music-audio',
                'description' => 'Voice over, audio editing, mixing, mastering, and music production',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business consulting, financial consulting, and data analysis services',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
