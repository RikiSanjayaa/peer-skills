<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Graphics & Design
            ['name' => 'Logo Design', 'category' => 'Graphics & Design'],
            ['name' => 'Brand Identity', 'category' => 'Graphics & Design'],
            ['name' => 'Illustration', 'category' => 'Graphics & Design'],
            ['name' => 'Graphic Design', 'category' => 'Graphics & Design'],
            ['name' => 'UI/UX Design', 'category' => 'Graphics & Design'],
            ['name' => 'Photoshop', 'category' => 'Graphics & Design'],
            ['name' => 'Adobe Illustrator', 'category' => 'Graphics & Design'],

            // Programming & Tech
            ['name' => 'Web Development', 'category' => 'Programming & Tech'],
            ['name' => 'Mobile App Development', 'category' => 'Programming & Tech'],
            ['name' => 'WordPress', 'category' => 'Programming & Tech'],
            ['name' => 'Laravel', 'category' => 'Programming & Tech'],
            ['name' => 'React', 'category' => 'Programming & Tech'],
            ['name' => 'Vue.js', 'category' => 'Programming & Tech'],
            ['name' => 'Python', 'category' => 'Programming & Tech'],
            ['name' => 'JavaScript', 'category' => 'Programming & Tech'],
            ['name' => 'PHP', 'category' => 'Programming & Tech'],
            ['name' => 'Database Design', 'category' => 'Programming & Tech'],
            ['name' => 'API Development', 'category' => 'Programming & Tech'],

            // Writing & Translation
            ['name' => 'Content Writing', 'category' => 'Writing & Translation'],
            ['name' => 'Copywriting', 'category' => 'Writing & Translation'],
            ['name' => 'Technical Writing', 'category' => 'Writing & Translation'],
            ['name' => 'Translation', 'category' => 'Writing & Translation'],
            ['name' => 'Proofreading', 'category' => 'Writing & Translation'],

            // Video & Animation
            ['name' => 'Video Editing', 'category' => 'Video & Animation'],
            ['name' => 'Motion Graphics', 'category' => 'Video & Animation'],
            ['name' => '3D Animation', 'category' => 'Video & Animation'],
            ['name' => 'After Effects', 'category' => 'Video & Animation'],

            // Digital Marketing
            ['name' => 'SEO', 'category' => 'Digital Marketing'],
            ['name' => 'Social Media Marketing', 'category' => 'Digital Marketing'],
            ['name' => 'Email Marketing', 'category' => 'Digital Marketing'],
            ['name' => 'Google Ads', 'category' => 'Digital Marketing'],
            ['name' => 'Facebook Ads', 'category' => 'Digital Marketing'],

            // Music & Audio
            ['name' => 'Voice Over', 'category' => 'Music & Audio'],
            ['name' => 'Audio Editing', 'category' => 'Music & Audio'],
            ['name' => 'Mixing & Mastering', 'category' => 'Music & Audio'],

            // Business
            ['name' => 'Business Consulting', 'category' => 'Business'],
            ['name' => 'Financial Consulting', 'category' => 'Business'],
            ['name' => 'Data Analysis', 'category' => 'Business'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
