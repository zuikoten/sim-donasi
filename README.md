# Sistem Informasi Donatur

Sistem Informasi Donatur adalah aplikasi web berbasis Laravel 11 untuk mengelola donasi ZISWAF (Zakat, Infaq, Sedekah, Wakaf) dengan fitur manajemen program donasi, verifikasi donasi, penyaluran dana, dan laporan transparansi.

## Fitur

### 1. Manajemen User & Role

-   Multi-role authentication (Superadmin, Admin, Donatur)
-   CRUD user (khusus superadmin)
-   CRUD role (khusus superadmin)
-   Seeder akun superadmin default

### 2. Program Donasi (ZISWAF)

-   CRUD program (oleh admin/superadmin)
-   Kategori program (Zakat, Infaq, Sedekah, Wakaf)
-   Progress bar untuk setiap program
-   Filter program berdasarkan kategori

### 3. Donasi

-   Form donasi untuk donatur
-   Upload bukti transfer
-   Verifikasi donasi oleh admin
-   Update otomatis dana terkumpul pada program
-   Notifikasi ke donatur saat status berubah

### 4. Penerima Manfaat

-   CRUD data penerima manfaat (oleh admin)
-   Relasi ke program donasi

### 5. Penyaluran Donasi

-   Pencatatan penyaluran donasi ke penerima
-   Update otomatis dana terdistribusi dari program
-   Notifikasi ke admin saat penyaluran dilakukan

### 6. Laporan & Dashboard

-   Dashboard dengan statistik donasi dan program
-   Grafik donasi bulanan
-   Filter laporan berdasarkan tanggal/program/status
-   Ekspor laporan ke PDF dan Excel

### 7. Notifikasi

-   Notifikasi saat donasi diverifikasi
-   Notifikasi saat program baru dibuat
-   Notifikasi saat penyaluran donasi dilakukan

### 8. Halaman Publik

-   Daftar program donasi dengan progress
-   Detail program
-   Laporan transparansi umum
-   Profil lembaga
-   Form kontak

## Persyaratan

-   PHP >= 8.2
-   Composer
-   MySQL/MariaDB
-   Node.js & NPM (untuk compiling assets)

## Instalasi

1. Clone repository

```bash
git clone https://github.com/username/donasi-app.git
cd donasi-app
```
