<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Database\Seeder;

class ChatMessageSeeder extends Seeder
{
    public function run(): void
    {
        // Get orders that are in active states (not pending, not cancelled)
        $orders = Order::whereIn('status', [
            Order::STATUS_QUOTED,
            Order::STATUS_ACCEPTED,
            Order::STATUS_DELIVERED,
            Order::STATUS_REVISION_REQUESTED,
            Order::STATUS_COMPLETED,
        ])->take(5)->get();

        if ($orders->isEmpty()) {
            $this->command->warn('No active orders found. Skipping chat seeder.');
            return;
        }

        $conversations = [
            // Conversation 1: Discussing requirements
            [
                ['is_buyer' => true, 'message' => 'Halo! Saya sudah submit order untuk project website saya.', 'delay' => 0],
                ['is_buyer' => false, 'message' => 'Halo! Terima kasih sudah order. Saya sudah lihat requirementsnya, ada beberapa pertanyaan.', 'delay' => 5],
                ['is_buyer' => false, 'message' => 'Untuk desain landing page, apakah sudah ada referensi atau wireframe yang bisa saya ikuti?', 'delay' => 7],
                ['is_buyer' => true, 'message' => 'Belum ada wireframe khusus, tapi saya suka style minimalis seperti website Apple atau Stripe.', 'delay' => 15],
                ['is_buyer' => true, 'message' => 'Warna utama yang saya mau adalah biru navy dan putih.', 'delay' => 17],
                ['is_buyer' => false, 'message' => 'Siap, saya paham! Saya akan buatkan 2-3 mockup design dulu sebelum mulai coding. Estimasi 2-3 hari untuk mockup.', 'delay' => 25],
                ['is_buyer' => true, 'message' => 'Oke, ditunggu ya! 👍', 'delay' => 30],
            ],
            // Conversation 2: Progress update
            [
                ['is_buyer' => false, 'message' => 'Update progress: Homepage sudah 70% selesai. Mau saya kirim screenshot?', 'delay' => 0],
                ['is_buyer' => true, 'message' => 'Boleh, kirim aja!', 'delay' => 10],
                ['is_buyer' => false, 'message' => 'Ini screenshot homepage-nya. Untuk menu navigasi, saya pakai sticky header supaya tetap visible saat scroll.', 'delay' => 15],
                ['is_buyer' => true, 'message' => 'Wah keren! Suka banget sama hero sectionnya. Tapi bisa gak font headingnya diganti yang lebih bold?', 'delay' => 45],
                ['is_buyer' => false, 'message' => 'Bisa dong! Saya ganti ke Poppins Bold ya. Nanti saya update.', 'delay' => 50],
                ['is_buyer' => true, 'message' => 'Perfect, thanks!', 'delay' => 55],
            ],
            // Conversation 3: Revision request
            [
                ['is_buyer' => true, 'message' => 'Hai, saya sudah review deliverynya. Ada beberapa revisi minor.', 'delay' => 0],
                ['is_buyer' => false, 'message' => 'Siap, silakan list revisinya!', 'delay' => 5],
                ['is_buyer' => true, 'message' => '1. Logo di footer kurang besar\n2. Contact form belum responsive di mobile\n3. Typo di halaman About', 'delay' => 10],
                ['is_buyer' => false, 'message' => 'Noted! Saya perbaiki sekarang. Estimasi 1-2 jam lagi selesai.', 'delay' => 15],
                ['is_buyer' => true, 'message' => 'Oke, gak buru-buru kok. Yang penting bener 😊', 'delay' => 20],
                ['is_buyer' => false, 'message' => 'Sudah selesai semua revisinya! Saya upload ulang ya.', 'delay' => 120],
                ['is_buyer' => true, 'message' => 'Mantap cepat banget! Saya cek dulu.', 'delay' => 125],
            ],
            // Conversation 4: Tutoring discussion
            [
                ['is_buyer' => true, 'message' => 'Kak, untuk sesi bimbingan nanti, saya mau fokus belajar React Hooks.', 'delay' => 0],
                ['is_buyer' => false, 'message' => 'Oke! Khususnya hooks yang mana? useState, useEffect, atau yang lebih advanced seperti useContext dan useReducer?', 'delay' => 10],
                ['is_buyer' => true, 'message' => 'Semuanya kalau bisa 😅 Tapi prioritas useState dan useEffect dulu.', 'delay' => 20],
                ['is_buyer' => false, 'message' => 'Siap, nanti saya siapkan materi dan mini project untuk latihan. Jangan lupa install Node.js dan VS Code sebelum sesi ya!', 'delay' => 25],
                ['is_buyer' => true, 'message' => 'Sudah ready semua! Besok jam 7 malam kan?', 'delay' => 60],
                ['is_buyer' => false, 'message' => 'Betul! Saya kirim link Zoom 15 menit sebelum mulai.', 'delay' => 65],
            ],
            // Conversation 5: Quick thank you
            [
                ['is_buyer' => true, 'message' => 'Hasil kerjanya bagus banget! Makasih banyak ya 🙏', 'delay' => 0],
                ['is_buyer' => false, 'message' => 'Sama-sama! Senang bisa bantu. Kalau ada project lain, jangan ragu hubungi lagi ya 😊', 'delay' => 5],
                ['is_buyer' => true, 'message' => 'Pasti! Sudah saya kasih rating 5 bintang.', 'delay' => 10],
                ['is_buyer' => false, 'message' => 'Terima kasih banyak! Sukses terus untuk projectnya! 🚀', 'delay' => 15],
            ],
        ];

        foreach ($orders as $index => $order) {
            $conversation = $conversations[$index % count($conversations)];
            $baseTime = now()->subHours(rand(2, 48));

            foreach ($conversation as $chat) {
                $senderId = $chat['is_buyer'] ? $order->buyer_id : $order->seller_id;

                ChatMessage::create([
                    'order_id' => $order->id,
                    'user_id' => $senderId,
                    'message' => $chat['message'],
                    'created_at' => $baseTime->copy()->addMinutes($chat['delay']),
                    'updated_at' => $baseTime->copy()->addMinutes($chat['delay']),
                ]);
            }

            $this->command->info("Created chat messages for Order #{$order->id}");
        }
    }
}
