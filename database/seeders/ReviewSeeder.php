<?php

namespace Database\Seeders;

use App\Models\Gig;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
  public function run(): void
  {
    // Get existing seller
    $seller = User::where('email', 'seller@example.com')->first();

    if (!$seller || !$seller->seller) {
      $this->command->error('Seller not found. Please run UserSeeder first.');
      return;
    }

    $gigs = Gig::where('seller_id', $seller->seller->id)->get();

    if ($gigs->isEmpty()) {
      $this->command->error('No gigs found. Please run GigSeeder first.');
      return;
    }

    // Create additional buyer users
    $buyers = [];

    $buyer1 = User::where('email', 'buyer@example.com')->first();
    if ($buyer1) {
      $buyers[] = $buyer1;
    }

    $buyerData = [
      [
        'name' => 'Buyer Two',
        'email' => 'buyer2@example.com',
      ],
      [
        'name' => 'Buyer Three',
        'email' => 'buyer3@example.com',
      ],
      [
        'name' => 'Buyer Four',
        'email' => 'buyer4@example.com',
      ],
    ];

    foreach ($buyerData as $data) {
      $buyer = User::firstOrCreate(
        ['email' => $data['email']],
        [
          'name' => $data['name'],
          'password' => Hash::make('password'),
          'email_verified_at' => now(),
          'is_seller' => false,
          'role' => 'user',
        ]
      );
      $buyers[] = $buyer;
    }

    // Review templates for variety
    $reviewTemplates = [
      5 => [
        'Sangat puas dengan hasilnya! Penjual sangat profesional dan responsif.',
        'Luar biasa! Melebihi ekspektasi saya. Pasti akan order lagi.',
        'Komunikasi lancar, hasil memuaskan. Recommended seller!',
        'Kerja cepat dan berkualitas. Terima kasih banyak!',
      ],
      4 => [
        'Bagus secara keseluruhan, ada sedikit revisi tapi seller responsif.',
        'Hasil bagus, pengerjaan tepat waktu. Recommended.',
        'Puas dengan layanannya, komunikasi baik.',
        'Kualitas bagus dengan harga yang reasonable.',
      ],
      3 => [
        'Cukup baik, sesuai dengan deskripsi layanan.',
        'Hasilnya oke, tapi butuh beberapa kali revisi.',
        'Standar, sesuai ekspektasi untuk harga segini.',
      ],
      2 => [
        'Kurang puas, hasil tidak sesuai ekspektasi.',
        'Perlu improvement dalam komunikasi.',
      ],
      1 => [
        'Sangat mengecewakan, tidak sesuai yang dijanjikan.',
      ],
    ];

    // Create completed orders and reviews for each buyer
    $orderIndex = 0;
    foreach ($buyers as $buyerIndex => $buyer) {
      // Each buyer will have 1-3 completed orders with reviews
      $numOrders = rand(1, 3);

      for ($i = 0; $i < $numOrders; $i++) {
        $gigIndex = ($orderIndex + $i) % $gigs->count();
        $gig = $gigs[$gigIndex];

        // Create completed order
        $order = Order::create([
          'buyer_id' => $buyer->id,
          'seller_id' => $seller->id,
          'gig_id' => $gig->id,
          'type' => Order::TYPE_STANDARD,
          'requirements' => $this->getRandomRequirement($gig->title),
          'price' => $gig->min_price + rand(0, 50000),
          'delivery_days' => $gig->delivery_days,
          'status' => Order::STATUS_COMPLETED,
          'seller_notes' => 'Terima kasih atas pesanannya!',
          'quoted_at' => now()->subDays(rand(20, 60)),
          'accepted_at' => now()->subDays(rand(15, 19)),
          'delivered_at' => now()->subDays(rand(5, 14)),
          'completed_at' => now()->subDays(rand(1, 4)),
          'created_at' => now()->subDays(rand(21, 65)),
        ]);

        // Create review with weighted randomness (more likely to be 4-5 stars)
        $rating = $this->getWeightedRating();
        $comments = $reviewTemplates[$rating];
        $comment = $comments[array_rand($comments)];

        Review::create([
          'order_id' => $order->id,
          'reviewer_id' => $buyer->id,
          'seller_id' => $seller->seller->id,
          'gig_id' => $gig->id,
          'rating' => $rating,
          'comment' => $comment,
          'created_at' => $order->completed_at->addHours(rand(1, 48)),
        ]);

        $orderIndex++;
      }
    }

    $this->command->info('Successfully created buyer users and reviews!');
    $this->command->info('New buyer accounts:');
    $this->command->info('  - buyer2@example.com (password: password)');
    $this->command->info('  - buyer3@example.com (password: password)');
    $this->command->info('  - buyer4@example.com (password: password)');
  }

  private function getWeightedRating(): int
  {
    // Weighted random: more likely to get 4-5 stars
    $weights = [
      5 => 40,  // 40% chance
      4 => 35,  // 35% chance
      3 => 15,  // 15% chance
      2 => 7,   // 7% chance
      1 => 3,   // 3% chance
    ];

    $total = array_sum($weights);
    $rand = rand(1, $total);

    $cumulative = 0;
    foreach ($weights as $rating => $weight) {
      $cumulative += $weight;
      if ($rand <= $cumulative) {
        return $rating;
      }
    }

    return 5;
  }

  private function getRandomRequirement(string $gigTitle): string
  {
    $requirements = [
      "Saya butuh layanan ini untuk project saya.\n\nDetail:\n- Sesuai dengan deskripsi layanan\n- Deadline flexible\n- Budget sesuai harga yang ditawarkan",
      "Hi! Saya tertarik dengan layanan Anda.\n\nKebutuhan saya:\n- Kualitas tinggi\n- Komunikasi yang baik\n- Revisi jika diperlukan",
      "Tolong bantu saya dengan project ini.\n\nPersyaratan:\n- Ikuti brief yang saya berikan\n- Update progress secara berkala\n- Hasil akhir dalam format yang diminta",
      "Saya membutuhkan bantuan profesional.\n\nHarapan:\n- Hasil berkualitas\n- Tepat waktu\n- Sesuai ekspektasi",
    ];

    return $requirements[array_rand($requirements)];
  }
}
