# PeerSkill

Platform marketplace freelance peer-to-peer yang menghubungkan mahasiswa untuk menawarkan dan membeli jasa (gig). Dibangun sebagai proyek tugas kuliah dengan pendekatan MVP (Minimum Viable Product) yang siap dikembangkan menjadi produk nyata.

## Tentang Proyek

PeerSkill adalah platform di mana mahasiswa dapat menjadi seller untuk menawarkan keahlian mereka (desain, programming, penulisan, dll) atau menjadi buyer untuk memesan jasa dari seller lain. Fitur unik yang membedakan dari platform serupa adalah opsi **tutoring** di mana seller dapat menawarkan sesi bimbingan langsung selain pengerjaan gig standar.

### Teknologi yang Digunakan

| Kategori       | Teknologi                       |
| -------------- | ------------------------------- |
| Backend        | Laravel 12, PHP 8.2+            |
| Frontend       | Blade, Bootstrap 5.3, Alpine.js |
| Database       | SQLite (development)            |
| Build Tool     | Vite                            |
| Authentication | Laravel Breeze                  |

## Instalasi

```bash
# Clone repository
git clone https://github.com/RikiSanjayaa/peer-skills.git
cd peer-skills

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat file database SQLite
touch database/database.sqlite

# Jalankan migrasi dan seeder
php artisan migrate --seed
php artisan storage:link

# Build assets dan jalankan server
npm run build
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

**Akun Demo:**

-   Buyer: `buyer@example.com` / `password`
-   Seller: `seller@example.com` / `password`

## Struktur Direktori

```
app/
├── Http/Controllers/
│   ├── GigController.php      # CRUD gig dan pencarian
│   ├── OrderController.php    # Alur order lengkap
│   ├── ProfileController.php  # Profil pengguna
│   └── SellerController.php   # Registrasi dan dashboard seller
├── Models/
│   ├── User.php               # Pengguna (buyer/seller)
│   ├── Seller.php             # Profil seller
│   ├── Gig.php                # Layanan yang ditawarkan
│   ├── Category.php           # Kategori gig
│   ├── Order.php              # Pesanan dengan status workflow
│   ├── OrderDelivery.php      # File hasil pengerjaan
│   └── TutoringSchedule.php   # Jadwal sesi tutoring
database/
├── migrations/                # Skema database
└── seeders/                   # Data dummy untuk testing
resources/views/
├── layouts/                   # Template utama
├── gigs/                      # Halaman gig (index, show, create, edit)
├── orders/                    # Halaman order (index, show, create)
├── seller/                    # Dashboard dan registrasi seller
└── profile/                   # Halaman profil
```

## Alur Penggunaan

### Registrasi dan Peran

1. User mendaftar melalui halaman register
2. Secara default, user adalah **buyer** yang dapat memesan gig
3. Buyer dapat mendaftar menjadi **seller** untuk menawarkan jasa
4. Satu akun dapat berperan sebagai buyer sekaligus seller

### Alur Order

Sistem order menggunakan mekanisme **penawaran harga** di mana seller mengajukan harga setelah melihat kebutuhan buyer:

```
[BUYER]                              [SELLER]
   |                                    |
   |-- 1. Buat pesanan (requirements) ->|
   |                                    |
   |<- 2. Ajukan penawaran (harga) -----|
   |                                    |
   |-- 3a. Terima penawaran ----------->|
   |   atau                             |
   |-- 3b. Tolak penawaran ------------>|
   |                                    |
   |<- 4. Kirim hasil (delivery) -------|
   |                                    |
   |-- 5a. Selesaikan pesanan --------->|
   |   atau                             |
   |-- 5b. Minta revisi --------------->|
   |                                    |
   |<- 6. Kirim ulang hasil ------------|
   |                                    |
   |-- 7. Selesaikan pesanan ---------->|
```

### Status Order

| Status               | Deskripsi                          |
| -------------------- | ---------------------------------- |
| `pending`            | Menunggu penawaran dari seller     |
| `quoted`             | Seller telah mengajukan penawaran  |
| `accepted`           | Buyer menerima, seller mengerjakan |
| `delivered`          | Seller mengirim hasil              |
| `revision_requested` | Buyer meminta revisi               |
| `completed`          | Pesanan selesai                    |
| `declined`           | Buyer menolak penawaran            |
| `cancelled`          | Pesanan dibatalkan                 |

### Fitur Tutoring (Opsional)

Untuk gig yang mengaktifkan opsi tutoring:

1. Buyer memilih tipe "Tutoring" saat memesan
2. Buyer mengajukan beberapa slot waktu
3. Seller mengkonfirmasi waktu dan mengirim link meeting
4. Setelah sesi selesai, seller menandai sebagai delivered

## Fitur Utama

-   **Pencarian dan Filter Gig** - Cari berdasarkan kata kunci, kategori, harga, waktu pengerjaan
-   **Live Search Suggestions** - Saran pencarian dengan preview gig
-   **Profil Seller** - Business name, keahlian, portfolio, universitas
-   **Dashboard Seller** - Statistik order, pendapatan, gig aktif
-   **Upload File** - Gambar gig, avatar, banner profil, file delivery
-   **Sistem Penawaran** - Negosiasi harga berdasarkan kebutuhan buyer

## Pengembangan Selanjutnya

Fitur yang direncanakan untuk pengembangan lanjutan:

-   Integrasi payment gateway
-   Real-time messaging antara buyer dan seller
-   Sistem rating dan review
-   Notifikasi email dan push notification
