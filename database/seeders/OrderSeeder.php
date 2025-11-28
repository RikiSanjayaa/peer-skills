<?php

namespace Database\Seeders;

use App\Models\Gig;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\TutoringSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get buyer and seller
        $buyer = User::where('email', 'buyer@example.com')->first();
        $seller = User::where('email', 'seller@example.com')->first();

        if (!$buyer || !$seller) {
            $this->command->error('Buyer or Seller not found. Please run UserSeeder first.');
            return;
        }

        // Get some gigs
        $gigs = Gig::where('seller_id', $seller->seller->id)->get();

        if ($gigs->isEmpty()) {
            $this->command->error('No gigs found. Please run GigSeeder first.');
            return;
        }

        // 1. Pending Order - Waiting for seller to quote
        Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[0]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "Hi! I need a logo for my new startup called 'TechFlow'. We're a tech consulting company.\n\nPreferences:\n- Modern and minimalist style\n- Blue and white color scheme\n- Should convey trust and innovation\n- Need it for website, business cards, and social media",
            'status' => Order::STATUS_PENDING,
            'created_at' => now()->subDays(1),
        ]);

        // 2. Quoted Order - Seller sent a quote, waiting for buyer
        Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[1]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "I need a portfolio website built with Laravel. Features needed:\n- About page\n- Projects gallery\n- Contact form\n- Blog section\n- Admin panel to manage content\n\nI already have the design mockups ready.",
            'price' => 350000,
            'delivery_days' => 7,
            'status' => Order::STATUS_QUOTED,
            'seller_notes' => "Thank you for reaching out! Based on your requirements, I can deliver a fully functional portfolio website. The price includes:\n- All features you mentioned\n- Responsive design\n- SEO optimization\n- 30 days support after delivery",
            'quoted_at' => now()->subHours(12),
            'created_at' => now()->subDays(2),
        ]);

        // 3. Accepted Order - In progress
        Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[2]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "I need 5 blog articles for my technology blog. Topics:\n1. AI in everyday life\n2. Future of remote work\n3. Cybersecurity tips for small businesses\n4. Cloud computing basics\n5. Green technology trends\n\nEach article should be around 1000-1500 words.",
            'price' => 75000,
            'delivery_days' => 5,
            'status' => Order::STATUS_ACCEPTED,
            'seller_notes' => "I'll write engaging, SEO-optimized articles on all these topics. Will deliver in batches of 2-3 articles.",
            'quoted_at' => now()->subDays(3),
            'accepted_at' => now()->subDays(2),
            'created_at' => now()->subDays(4),
        ]);

        // 4. Delivered Order - Waiting for buyer to accept
        $deliveredOrder = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[0]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "Logo for my bakery 'Sweet Dreams'. Should be:\n- Warm and inviting colors (pink, cream)\n- Include a cupcake or pastry icon\n- Elegant but approachable font",
            'price' => 100000,
            'delivery_days' => 3,
            'status' => Order::STATUS_DELIVERED,
            'seller_notes' => "I'll create 3 unique concepts for you to choose from!",
            'quoted_at' => now()->subDays(6),
            'accepted_at' => now()->subDays(5),
            'delivered_at' => now()->subDays(1),
            'created_at' => now()->subDays(7),
        ]);

        // Add delivery for the delivered order
        OrderDelivery::create([
            'order_id' => $deliveredOrder->id,
            'file_path' => 'deliveries/sample-logo-delivery.zip',
            'file_name' => 'sweet-dreams-logo-final.zip',
            'message' => "Here's your logo! I've included:\n- 3 logo variations\n- All vector files (AI, EPS, SVG)\n- PNG files in various sizes\n- Color palette guide\n\nLet me know if you need any revisions!",
            'is_final' => true,
        ]);

        // 5. Revision Requested Order
        $revisionOrder = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[4]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "Edit my YouTube video (15 minutes raw footage). Need:\n- Smooth transitions\n- Background music\n- Subtitles\n- Intro and outro\n- Color correction",
            'price' => 150000,
            'delivery_days' => 5,
            'status' => Order::STATUS_REVISION_REQUESTED,
            'seller_notes' => "I'll create a professional edit with all the effects you need!",
            'quoted_at' => now()->subDays(8),
            'accepted_at' => now()->subDays(7),
            'delivered_at' => now()->subDays(2),
            'created_at' => now()->subDays(9),
        ]);

        OrderDelivery::create([
            'order_id' => $revisionOrder->id,
            'file_path' => 'deliveries/video-edit-v1.mp4',
            'file_name' => 'youtube-video-edited-v1.mp4',
            'message' => "First version is ready! Added all transitions, music, and color correction. Let me know what you think!",
            'is_final' => false,
        ]);

        // 6. Completed Order
        $completedOrder = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[3]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "Manage my Instagram account for 1 month. I have a fashion boutique. Need:\n- 3 posts per week\n- Story content\n- Engagement with followers\n- Monthly report",
            'price' => 200000,
            'delivery_days' => 30,
            'status' => Order::STATUS_COMPLETED,
            'seller_notes' => "I'll create a content calendar and handle everything for your Instagram growth!",
            'quoted_at' => now()->subDays(35),
            'accepted_at' => now()->subDays(34),
            'delivered_at' => now()->subDays(3),
            'completed_at' => now()->subDays(2),
            'created_at' => now()->subDays(36),
        ]);

        OrderDelivery::create([
            'order_id' => $completedOrder->id,
            'file_path' => 'deliveries/instagram-report.pdf',
            'file_name' => 'monthly-instagram-report.pdf',
            'message' => "Month complete! Here's your report:\n- Gained 500+ new followers\n- Engagement rate increased by 25%\n- Top performing posts analysis\n- Recommendations for next month",
            'is_final' => true,
        ]);

        // 7. Cancelled Order
        Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[1]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "Need a simple landing page for my event.",
            'status' => Order::STATUS_CANCELLED,
            'created_at' => now()->subDays(10),
        ]);

        // 8. Declined Order
        Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gigs[2]->id,
            'type' => Order::TYPE_STANDARD,
            'requirements' => "I need 50 articles written in 2 days about medical topics.",
            'price' => 500000,
            'delivery_days' => 2,
            'status' => Order::STATUS_DECLINED,
            'seller_notes' => "This timeline is not feasible for quality medical content. I need at least 2 weeks for this volume.",
            'quoted_at' => now()->subDays(12),
            'created_at' => now()->subDays(14),
        ]);

        // 9. Tutoring Order - Pending schedule
        if ($gigs->count() > 1 && $gigs[1]->allows_tutoring) {
            $tutoringOrder = Order::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'gig_id' => $gigs[1]->id,
                'type' => Order::TYPE_TUTORING,
                'requirements' => "I want to learn Laravel basics. I'm a beginner with some PHP experience.\n\nTopics I want to cover:\n- MVC architecture\n- Routing and controllers\n- Eloquent ORM\n- Blade templating",
                'price' => 250000,
                'delivery_days' => 1,
                'status' => Order::STATUS_ACCEPTED,
                'seller_notes' => "Great! I'll prepare a structured learning session for you. We'll cover all the basics in a 2-hour session.",
                'quoted_at' => now()->subDays(1),
                'accepted_at' => now()->subHours(6),
                'created_at' => now()->subDays(2),
            ]);

            TutoringSchedule::create([
                'order_id' => $tutoringOrder->id,
                'proposed_slots' => [
                    'Saturday, Dec 2nd at 10:00 AM',
                    'Sunday, Dec 3rd at 2:00 PM',
                    'Monday, Dec 4th at 7:00 PM',
                ],
                'confirmed_slot' => 'Saturday, Dec 2nd at 10:00 AM',
                'external_link' => 'https://meet.google.com/abc-defg-hij',
                'topic' => 'Laravel Basics for Beginners',
            ]);
        }

        $this->command->info('Successfully created sample orders with various statuses!');
    }
}
