# 🌿 Ruang Bonsai - E-commerce Platform

**Ruang Bonsai** adalah platform e-commerce khusus untuk para pecinta bonsai yang memudahkan pengguna dalam mencari, membeli, dan memesan berbagai jenis bonsai beserta perlengkapannya secara online.

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [API yang Digunakan](#-api-yang-digunakan)
- [Struktur Proyek](#-struktur-proyek)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

## ✨ Fitur Utama

- 🛍️ **Katalog Produk**: Jelajahi berbagai jenis bonsai dan perlengkapannya
- 🔍 **Pencarian & Filter**: Cari produk berdasarkan kategori, harga, dan spesifikasi
- 🛒 **Keranjang Belanja**: Kelola produk yang ingin dibeli
- 💳 **Payment Gateway**: Integrasi dengan Midtrans untuk pembayaran yang aman
- 🚚 **Cek Ongkos Kirim**: Integrasi dengan RajaOngkir untuk kalkulasi ongkir
- 📱 **Responsive Design**: Tampilan yang optimal di semua perangkat
- 👤 **Manajemen User**: Registrasi, login, dan profil pengguna
- 📦 **Tracking Pesanan**: Pantau status pesanan secara real-time
- 💬 **Sistem Review**: Berikan dan baca ulasan produk

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 10.x** - PHP Framework
- **MySQL** - Database
- **PHP 8.1+** - Programming Language

### Frontend
- **Tailwind CSS** - Utility-first CSS Framework
- **Blade Templates** - Laravel's Templating Engine

### API Integration
- **Midtrans** - Payment Gateway
- **RajaOngkir** - Shipping Cost Calculator
- **Fonnte** - WhatsApp API Gateway

## 📋 Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM atau Yarn
- MySQL >= 5.7
- Web Server (Apache)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/ruang-bonsai.git
cd ruang-bonsai
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies Node.js

```bash
npm install
# atau menggunakan yarn
yarn install
```

### 4. Copy Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Buat Database

Buat database MySQL baru dengan nama `ruang_bonsai` atau sesuai keinginan Anda.

### 7. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ruang_bonsai
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 8. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

### 9. Build Assets

```bash
npm run build
# untuk development
npm run dev
```

## ⚙️ Konfigurasi

### Konfigurasi Environment

Edit file `.env` dan sesuaikan konfigurasi yang diperlukan:

```env
# Application Configuration
APP_NAME="Ruang Bonsai"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Mail Configuration (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@ruangbonsai.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🏃‍♂️ Menjalankan Aplikasi

### Development Server

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Asset Watcher 
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`
```

## 🔌 API yang Digunakan

### Midtrans Payment Gateway

Digunakan untuk memproses pembayaran dengan berbagai metode:
- Credit Card
- Bank Transfer
- E-Wallet (GoPay, OVO, DANA)
- Convenience Store

### RajaOngkir Shipping API

Digunakan untuk:
- Menghitung ongkos kirim berbagai ekspedisi
- Cek resi pengiriman
- Mendapatkan daftar kota dan provinsi

### Fonnte WhatsApp API

Digunakan untuk:
- Notifikasi pesanan via WhatsApp
- Customer service automation
- Marketing broadcast

---

**Ruang Bonsai** - Bringing Nature's Art to Your Doorstep 🌿

*Dibuat oleh Ferdi Nuraripin*
