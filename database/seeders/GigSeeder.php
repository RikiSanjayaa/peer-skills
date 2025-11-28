<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gig;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class GigSeeder extends Seeder
{
    public function run(): void
    {
        // Get the existing seller (seller@example.com)
        $seller = Seller::whereHas('user', function ($query) {
            $query->where('email', 'seller@example.com');
        })->first();

        if (!$seller) {
            $this->command->error('Seller not found. Please run UserSeeder first.');
            return;
        }

        // Get categories
        $graphicsDesign = Category::where('slug', 'graphics-design')->first();
        $programmingTech = Category::where('slug', 'programming-tech')->first();
        $writingTranslation = Category::where('slug', 'writing-translation')->first();
        $videoAnimation = Category::where('slug', 'video-animation')->first();
        $digitalMarketing = Category::where('slug', 'digital-marketing')->first();

        // Available images from storage
        $availableImages = [
            'gigs/images/07umt8kDA5UcfgTA7QUUtf2u4cLlsYcUaptdw3Q4.jpg',
            'gigs/images/christopher-gower-m_HRfLhgABo-unsplash.jpg',
            'gigs/images/michal-parzuchowski-Nh4Sxasye24-unsplash.jpg',
        ];

        $gigs = [
            [
                'seller_id' => $seller->id,
                'category_id' => $graphicsDesign->id,
                'title' => 'I will design a modern and professional logo for your brand',
                'description' => "Looking for a unique logo that represents your brand perfectly? I'll create a custom logo design that captures your brand's essence.\n\nWhat you'll get:\n- 3 initial concepts\n- Unlimited revisions\n- Vector files (AI, EPS, SVG)\n- High-resolution PNG and JPG\n- Source files\n- Commercial rights\n\nI specialize in minimalist, modern, and timeless designs. Let's bring your vision to life!",
                'min_price' => 75000,
                'max_price' => 250000,
                'delivery_days' => 3,
                'allows_tutoring' => false,
                'images' => [$availableImages[0]],
                'attachments' => null,
            ],
            [
                'seller_id' => $seller->id,
                'category_id' => $programmingTech->id,
                'title' => 'I will develop a responsive website using Laravel and Bootstrap',
                'description' => "Need a professional website for your business? I'll build a fast, secure, and responsive website tailored to your needs.\n\nServices include:\n- Custom Laravel development\n- Responsive design (mobile-friendly)\n- Database integration\n- User authentication\n- Admin panel\n- SEO optimization\n- 30 days of free support\n\nPerfect for business websites, portfolios, and web applications.",
                'min_price' => 500000,
                'max_price' => 2000000,
                'delivery_days' => 7,
                'allows_tutoring' => true,
                'images' => [$availableImages[1]],
                'attachments' => null,
            ],
            [
                'seller_id' => $seller->id,
                'category_id' => $writingTranslation->id,
                'title' => 'I will write engaging SEO-optimized content for your website or blog',
                'description' => "Boost your online presence with high-quality, SEO-optimized content that engages readers and ranks well on search engines.\n\nWhat I offer:\n- Thoroughly researched articles\n- SEO keyword integration\n- Engaging and original content\n- Proper formatting with headings\n- Plagiarism-free guarantee\n- Up to 2 revisions\n\nTopics I cover: Technology, Business, Health, Lifestyle, Travel, and more!",
                'min_price' => 50000,
                'max_price' => 150000,
                'delivery_days' => 3,
                'allows_tutoring' => false,
                'images' => [$availableImages[2]],
                'attachments' => null,
            ],
            [
                'seller_id' => $seller->id,
                'category_id' => $digitalMarketing->id,
                'title' => 'I will create and manage your social media marketing campaigns',
                'description' => "Grow your business with professional social media marketing! I'll help you reach your target audience and increase engagement.\n\nServices:\n- Content calendar planning\n- Post creation and scheduling\n- Hashtag research\n- Community management\n- Monthly analytics reports\n- Facebook, Instagram, Twitter, LinkedIn\n\nGet more followers, engagement, and conversions!",
                'min_price' => 200000,
                'max_price' => 500000,
                'delivery_days' => 14,
                'allows_tutoring' => true,
                'images' => null, // Will show default placeholder
                'attachments' => null,
            ],
            [
                'seller_id' => $seller->id,
                'category_id' => $videoAnimation->id,
                'title' => 'I will edit your videos professionally with transitions and effects',
                'description' => "Transform your raw footage into polished, professional videos! I provide high-quality video editing services for YouTube, social media, and more.\n\nIncludes:\n- Color correction and grading\n- Audio enhancement\n- Transitions and effects\n- Text and titles\n- Background music\n- Subtitles/captions\n- Fast delivery\n\nSupported formats: MP4, MOV, AVI, and more. Let's make your content stand out!",
                'min_price' => 100000,
                'max_price' => 350000,
                'delivery_days' => 7,
                'allows_tutoring' => false,
                'images' => null, // Will show default placeholder
                'attachments' => null,
            ],
            [
                'seller_id' => $seller->id,
                'category_id' => $programmingTech->id,
                'title' => 'I will build a custom mobile app using React Native',
                'description' => "Get a cross-platform mobile app that works seamlessly on both iOS and Android! I specialize in React Native development.\n\nWhat you get:\n- Native performance\n- Cross-platform compatibility\n- Clean, maintainable code\n- API integration\n- Push notifications\n- App store deployment assistance\n- Source code included\n\nPerfect for startups and businesses looking to go mobile!",
                'min_price' => 1500000,
                'max_price' => 5000000,
                'delivery_days' => 14,
                'allows_tutoring' => true,
                'images' => null,
                'attachments' => null,
            ],
        ];

        foreach ($gigs as $gigData) {
            Gig::create($gigData);
        }

        $this->command->info('Successfully created ' . count($gigs) . ' sample gigs!');
    }
}
