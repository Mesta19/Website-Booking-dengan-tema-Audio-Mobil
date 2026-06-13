# Global Service Audio Mobil — Sistem Booking Online

Aplikasi web manajemen layanan dan booking servis audio mobil berbasis **CodeIgniter 4**, ditenagai **PHP** dan **MySQL** via **XAMPP**.

---

## Tampilan Aplikasi (Prototype)

### Beranda
![Halaman Beranda](https://raw.githubusercontent.com/Mesta19/projek-analisa/refs/heads/main/docs/beranda.png)

Halaman utama menampilkan hero section, keunggulan layanan, tautan marketplace (Shopee & Tokopedia), serta informasi kontak.

### Login Pelanggan
![Halaman Login](https://raw.githubusercontent.com/Mesta19/projek-analisa/refs/heads/main/docs/login.png)

Halaman autentikasi pelanggan dengan navigasi menuju registrasi dan login khusus Admin.

### Formulir Booking Layanan
![Halaman Booking](https://raw.githubusercontent.com/Mesta19/projek-analisa/refs/heads/main/docs/booking.png)

Pelanggan yang sudah login dapat memilih tanggal booking dan satu atau lebih layanan sekaligus.

---

## Fitur Utama

| Fitur | Keterangan |
|---|---|
| Halaman Beranda | Informasi umum bisnis, link marketplace, kontak |
| Daftar Layanan | Menampilkan layanan aktif beserta harga |
| Booking Layanan | Form booking multi-layanan dengan validasi |
| Autentikasi | Login & Register terpisah untuk Pelanggan dan Admin |
| Riwayat Booking | Pelanggan dapat melihat dan membatalkan booking |
| Dashboard Admin | Manajemen semua booking dan data pelanggan |
| CRUD Layanan | Admin dapat tambah, edit, dan hapus layanan |

---

## Arsitektur Proyek

### Stack Teknologi

```
┌─────────────────────────────────────────┐
│              Browser / Client           │
│         (HTML, CSS, JavaScript)         │
└──────────────────┬──────────────────────┘
                   │ HTTP Request
┌──────────────────▼──────────────────────┐
│         PHP Built-in Server             │
│         (php spark serve)               │
│         localhost:8080                  │
└──────────────────┬──────────────────────┘
                   │
┌──────────────────▼──────────────────────┐
│         CodeIgniter 4 Framework         │
│                                         │
│  ┌──────────┐  ┌──────────┐  ┌───────┐ │
│  │  Routes  │→ │Controller│→ │ View  │ │
│  └──────────┘  └────┬─────┘  └───────┘ │
│                     │                   │
│               ┌─────▼─────┐             │
│               │   Model   │             │
│               └─────┬─────┘             │
└─────────────────────┼───────────────────┘
                       │ MySQLi
┌─────────────────────▼───────────────────┐
│              XAMPP                       │
│         MySQL / MariaDB                  │
│         Database: bookingservis          │
│         Port: 3306                       │
└─────────────────────────────────────────┘
```

### Pola Arsitektur: MVC (Model-View-Controller)

```
projek-analisa/
│
├── app/
│   ├── Controllers/          # Logika bisnis & handler HTTP
│   │   ├── HomeController.php       → Halaman beranda
│   │   ├── AuthController.php       → Login & Register (Pelanggan & Admin)
│   │   ├── BookingController.php    → Proses booking layanan
│   │   ├── LayananController.php    → CRUD layanan (publik & admin)
│   │   └── Admin.php                → Dashboard & manajemen admin
│   │
│   ├── Models/               # Interaksi dengan database
│   │   ├── PelangganModel.php
│   │   ├── AdminModel.php
│   │   ├── LayananModel.php
│   │   ├── BookingModel.php
│   │   └── DetailBookingModel.php
│   │
│   ├── Views/                # Template tampilan (HTML + PHP)
│   │   ├── beranda.php
│   │   ├── auth/             → Form login & register
│   │   ├── booking/          → Form & riwayat booking
│   │   ├── layanan/          → Daftar layanan publik
│   │   ├── admin/            → Dashboard & panel admin
│   │   └── template/         → Layout & komponen bersama
│   │
│   ├── Filters/              # Middleware autentikasi
│   │   └── AdminAuth.php            → Proteksi rute admin
│   │
│   ├── Config/
│   │   └── Routes.php               → Definisi semua rute aplikasi
│   │
│   └── Database/
│       ├── Migrations/              → Skema tabel database
│       └── Seeds/                   → Data awal (opsional)
│
├── public/                   # Document root (aset publik)
├── vendor/                   # Dependensi Composer
├── .env                      # Konfigurasi environment (DB, URL)
├── composer.json             # Deklarasi dependensi PHP
└── spark                     # CLI tool CodeIgniter
```

---

## Skema Database

Database: **`bookingservis`** (MySQL via XAMPP)

```
pelanggan
├── id_pelanggan  (PK)
├── nama_pelanggan
├── email
└── password

admin
├── id_admin  (PK)
├── nama_admin
├── email
└── password

layanan
├── id_layanan        (PK)
├── nama_layanan
├── harga
└── is_delete_layanan  (soft delete)

booking
├── id_booking        (PK)
├── id_pelanggan      (FK → pelanggan)
├── tanggal_booking
└── total_harga

detail_booking
├── id_detail      (PK)
├── id_booking     (FK → booking)
└── id_layanan     (FK → layanan)
```

**Relasi:**
- Satu `pelanggan` dapat memiliki banyak `booking` (One-to-Many)
- Satu `booking` dapat mencakup banyak `layanan` melalui `detail_booking` (Many-to-Many)

---

## Cara Instalasi & Menjalankan

### Prasyarat

Pastikan sudah terinstal:
- [XAMPP](https://www.apachefriends.org/) (dengan MySQL aktif)
- [PHP >= 8.1](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- Git

### Langkah 1 — Clone Repositori

```bash
git clone https://github.com/Mesta19/projek-analisa.git
cd projek-analisa
```

### Langkah 2 — Install Dependensi

```bash
composer install
```

### Langkah 3 — Konfigurasi Environment

Salin file env dan sesuaikan:

```bash
cp env .env
```

Edit `.env`:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080'

database.default.hostname = 127.0.0.1
database.default.database = bookingservis
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### Langkah 4 — Siapkan Database

1. Buka **XAMPP Control Panel** → Start **Apache** dan **MySQL**
2. Buka **phpMyAdmin** di `http://localhost/phpmyadmin`
3. Buat database baru dengan nama `bookingservis`
4. Jalankan migrasi (opsional jika tersedia):

```bash
php spark migrate
```

### Langkah 5 — Jalankan Server

```bash
php spark serve
```

Akses aplikasi di browser:

```
http://localhost:8080
```

---

## Daftar Rute (Routes)

| Method | URI | Controller | Keterangan |
|---|---|---|---|
| GET | `/` | HomeController::index | Halaman Beranda |
| GET | `/layanan` | LayananController::indexPublik | Daftar Layanan Publik |
| GET | `/register-pelanggan` | AuthController::tampilkanRegistrasiPelanggan | Form Registrasi |
| POST | `/register-pelanggan` | AuthController::prosesRegistrasiPelanggan | Proses Registrasi |
| GET | `/login-pelanggan` | AuthController::tampilkanLoginPelanggan | Form Login Pelanggan |
| POST | `/login-pelanggan` | AuthController::prosesLoginPelanggan | Proses Login |
| GET | `/logout-pelanggan` | AuthController::logoutPelanggan | Logout Pelanggan |
| GET | `/booking/form` | BookingController::tampilkanFormBooking | Form Booking |
| POST | `/booking/proses` | BookingController::prosesBooking | Proses Booking |
| GET | `/booking/saya` | BookingController::daftarBookingPelanggan | Riwayat Booking |
| POST | `/booking/hapus/:id` | BookingController::hapusBookingPelanggan | Batalkan Booking |
| GET | `/admin/login` | AuthController::tampilkanLoginAdmin | Login Admin |
| GET | `/admin/dashboard` | Admin::dashboard | Dashboard Admin *(Auth)* |
| GET | `/admin/layanan/` | LayananController::adminIndex | Daftar Layanan Admin |
| GET | `/admin/layanan/tambah` | LayananController::adminTambah | Form Tambah Layanan |
| POST | `/admin/layanan/simpan` | LayananController::adminSimpan | Simpan Layanan |
| GET | `/admin/layanan/edit/:id` | LayananController::adminEdit | Form Edit Layanan |
| POST | `/admin/layanan/update/:id` | LayananController::adminUpdate | Update Layanan |
| POST | `/admin/layanan/hapus/:id` | LayananController::adminHapus | Hapus Layanan |
| POST | `/admin/booking/hapus/:id` | Admin::hapusBooking | Hapus Booking *(Auth)* |

---

## Sistem Autentikasi

Aplikasi menggunakan **Session-based Authentication** dengan dua peran:

- **Pelanggan** — Dapat login, registrasi, booking layanan, dan melihat riwayat booking
- **Admin** — Dapat login melalui `/admin/login`, mengelola semua booking, data pelanggan, dan CRUD layanan

Proteksi rute admin menggunakan Filter `AdminAuth.php` yang memvalidasi sesi sebelum mengizinkan akses.

---

## Teknologi yang Digunakan

| Teknologi | Versi | Peran |
|---|---|---|
| CodeIgniter 4 | ^4.0 | PHP Framework (MVC) |
| PHP | ^8.1 | Bahasa Pemrograman Server-side |
| MySQL / MariaDB | via XAMPP | Sistem Manajemen Database |
| XAMPP | Latest | Local Development Environment |
| Composer | Latest | Package Manager PHP |
| `php spark serve` | Built-in | Development server CodeIgniter |

---

## Perintah CLI Berguna (`php spark`)

```bash
# Menjalankan server development
php spark serve

# Menjalankan migrasi database
php spark migrate

# Membuat Controller baru
php spark make:controller NamaController

# Membuat Model baru
php spark make:model NamaModel

# Membuat Migration baru
php spark make:migration NamaMigration

# Melihat semua rute terdaftar
php spark routes

# Melihat versi CodeIgniter
php spark --version
```

---

## Kontak Bisnis

| Platform | Info |
|---|---|
| Email | globalservice4545@gmail.com |
| WhatsApp | +62 878 8162 0835 |
| Shopee | [Car Audio Stereo](https://shopee.co.id) |
| Tokopedia | [Car Audio Stereo](https://tokopedia.com) |

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

© 2026 Global Service Audio Mobil. Hak Cipta Dilindungi.
