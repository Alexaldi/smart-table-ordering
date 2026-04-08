# 🍽️ Smart Table Ordering

Sistem pemesanan digital berbasis QR Code untuk UMKM kuliner. Customer scan QR di meja → pilih menu → pesan. Pesanan masuk ke dapur & kasir secara **realtime**.

## Tech Stack

- **Laravel 11** — Backend
- **Blade + Alpine.js** — Frontend
- **TailwindCSS** — Styling
- **Laravel Reverb** — Realtime / WebSocket
- **MySQL** — Database

## Fitur

- Customer pesan via scan QR, tanpa login
- Notifikasi order realtime ke dapur & kasir
- Multi-role: Owner, Kasir, Dapur
- Auto-generate QR Code per meja
- Laporan penjualan + export PDF
- Print bill otomatis

## Cara Menjalankan

```bash
git clone https://github.com/rzkyftrhmn/smart-table-ordering.git
cd smart-table-ordering

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
# Sesuaikan konfigurasi DB di .env

php artisan migrate --seed
php artisan reverb:start
php artisan serve
```

### Akun Demo

| Role | Email | Password |
|---|---|---|
| Owner | owner@demo.com | password |
| Kasir | kasir@demo.com | password |
| Dapur | dapur@demo.com | password |

> Customer tidak perlu login — cukup scan QR Code permeja.
