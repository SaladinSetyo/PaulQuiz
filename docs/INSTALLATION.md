# Panduan Instalasi PaulQuiz

Dokumen ini menjelaskan cara menjalankan PaulQuiz di komputer lokal, baik dengan PHP terpasang langsung (native) maupun dengan Docker.

← Kembali ke [README](../README.md)

---

## Daftar Isi

- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Opsi A: Instalasi Native](#opsi-a-instalasi-native)
- [Opsi B: Menggunakan Docker](#opsi-b-menggunakan-docker)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Data Awal (Seeder)](#data-awal-seeder)
- [Menjalankan Test](#menjalankan-test)
- [Troubleshooting](#troubleshooting)

---

## Kebutuhan Sistem

| Komponen | Versi | Catatan |
|---|---|---|
| PHP | 8.2 – 8.4 | `composer.lock` saat ini belum kompatibel dengan PHP 8.5 (dependensi `brianium/paratest`). |
| Ekstensi PHP | `mbstring`, `xml`/`dom`, `curl`, `zip`, `openssl`, `pdo_sqlite` (atau `pdo_mysql`) | Di Debian/Ubuntu: `sudo apt install php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-sqlite3` |
| Composer | 2.x | |
| Node.js | 20+ | Diuji dengan Node 24 & npm 12 |
| Database | SQLite (default) atau MySQL 8 | |

---

## Opsi A: Instalasi Native

```bash
git clone https://github.com/SaladinSetyo/PaulQuiz.git
cd PaulQuiz

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env` untuk lokal (lihat [Konfigurasi Environment](#konfigurasi-environment)), lalu:

```bash
touch database/database.sqlite
php artisan migrate --seed
npm run build
php artisan serve
```

Aplikasi tersedia di **http://localhost:8000**.

### Mode pengembangan

```bash
composer run dev
```

Perintah ini menjalankan empat proses sekaligus lewat `concurrently`:

| Proses | Perintah |
|---|---|
| Web server | `php artisan serve` |
| Queue worker | `php artisan queue:listen --tries=1` |
| Log viewer | `php artisan pail` |
| Vite (hot reload) | `npm run dev` |

---

## Opsi B: Menggunakan Docker

Cocok jika PHP di komputer Anda tidak memiliki ekstensi yang dibutuhkan atau versinya tidak sesuai. Node.js tetap dijalankan dari host.

> Image resmi `composer:2` saat ini memakai PHP 8.5 sehingga `composer install` akan ditolak oleh lock file. Karena itu dipakai image `php:8.4-cli` yang ditambah Composer.

**1. Build image PHP 8.4 + Composer (cukup sekali)**

```bash
docker build -t paulquiz-php - <<'EOF'
FROM php:8.4-cli
RUN apt-get update && apt-get install -y --no-install-recommends unzip git libzip-dev \
    && docker-php-ext-install zip && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
EOF
```

**2. Buat alias agar perintah lebih ringkas** (dijalankan dari folder project)

```bash
alias dphp='docker run --rm -it -u $(id -u):$(id -g) -e HOME=/tmp -e COMPOSER_HOME=/tmp/composer -v "$PWD":/app -w /app paulquiz-php'
```

**3. Instalasi**

```bash
dphp composer install
npm install

cp .env.example .env
dphp php artisan key:generate
touch database/database.sqlite
dphp php artisan migrate --seed
npm run build
```

**4. Jalankan server**

```bash
docker run --rm -it --name paulquiz -u $(id -u):$(id -g) -p 8000:8000 -v "$PWD":/app -w /app paulquiz-php php artisan serve --host=0.0.0.0 --port=8000
```

Buka **http://localhost:8000**. Hentikan dengan `Ctrl+C`.

---

## Konfigurasi Environment

`.env.example` disiapkan untuk produksi (SMTP SendGrid, `APP_DEBUG=false`). Untuk lokal, ubah nilai berikut:

```dotenv
APP_NAME=PaulQuiz
APP_DEBUG=true
APP_URL=http://localhost:8000

# Email ditulis ke storage/logs/laravel.log, tidak benar-benar dikirim
MAIL_MAILER=log
```

### Menggunakan MySQL

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paulquiz
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `paulquiz` terlebih dahulu, lalu jalankan `php artisan migrate --seed`.

### Email (SMTP)

Aplikasi mengirim email untuk **verifikasi akun** dan **reset password** dengan template ber-branding PaulQuiz (`resources/views/vendor/mail`). Konfigurasi produksi memakai SendGrid:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=<sendgrid-api-key>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@domain-anda.com"
```

Dengan `MAIL_MAILER=log`, tautan verifikasi dan reset password bisa disalin dari `storage/logs/laravel.log`.

### Session, cache & queue

Default-nya memakai driver `database` (tabel dibuat oleh migrasi), jadi tidak perlu Redis untuk menjalankan aplikasi secara lokal.

---

## Data Awal (Seeder)

`php artisan migrate --seed` menjalankan `DatabaseSeeder`, yang memanggil:

| Seeder | Isi |
|---|---|
| `RolesAndPermissionsSeeder` | Role `admin` & `user` (Spatie) + akun demo `admin@example.com` (kolom `role` = `admin`) dan `user@example.com` (password `password`, sudah terverifikasi). Aman dijalankan ulang. |
| `ModuleSeeder` | 4 modul: *Apa itu fintech*, *Jenis-jenis Fintech berikut contohnya*, *Keamanan digital dan privasi*, *Regulasi dan perlindungan*. |
| `ContentSeeder` | Modul 1: 1 artikel, 3 video YouTube, 3 infografis (ditandai *featured*). |
| `QuizSeeder` | Kuis *Kuis Dasar Fintech* (3 soal pilihan ganda) di Modul 1. |

### Menjadikan pengguna sebagai admin

Middleware `role:admin` memeriksa kolom `users.role`. Untuk mempromosikan pengguna lain menjadi admin, set kolom tersebut sekaligus role Spatie-nya:

```bash
php artisan tinker --execute="\$u = App\Models\User::where('email', 'nama@email.com')->first(); \$u->update(['role' => 'admin']); \$u->assignRole('admin');"
```

### Reset data

```bash
php artisan migrate:fresh --seed
```

---

## Menjalankan Test

```bash
php artisan test
```

Test memakai SQLite in-memory (lihat `phpunit.xml`), jadi tidak menyentuh database lokal.

| Suite | Cakupan |
|---|---|
| `Feature/Auth/*` | Login (termasuk input email tetap terisi setelah gagal login), logout, register, verifikasi email, konfirmasi & update password, reset password dengan `CustomResetPassword` |
| `Feature/QuizScoringTest` | Poin hanya dari skor terbaik per kuis, percobaan ulang hanya menambah selisih, jawaban dari kuis lain tidak dihitung |
| `Feature/AdminAccessTest` | Akun admin hasil seeder bisa membuka `/admin`; pengguna biasa mendapat 403 |
| `Feature/ProfileTest` | Update profil & hapus akun |
| `Feature/ExampleTest`, `Unit/ExampleTest` | Smoke test halaman utama |

**Hasil saat ini: 32 test lulus (70 assertions).**

---

## Troubleshooting

| Gejala | Penyebab & Solusi |
|---|---|
| `composer install`: *your php version (8.5.x) does not satisfy that requirement* | Gunakan PHP 8.2–8.4, atau ikuti [Opsi B](#opsi-b-menggunakan-docker). |
| `composer install` gagal karena `ext-mbstring` / `ext-dom` | Instal ekstensi PHP yang kurang (lihat [Kebutuhan Sistem](#kebutuhan-sistem)). |
| `Vite manifest not found` | Jalankan `npm run build` (atau `npm run dev` saat pengembangan). |
| `database.sqlite does not exist` | `touch database/database.sqlite` lalu migrasi ulang. |
| `/admin` menampilkan **403** untuk akun admin | Kolom `users.role` belum `admin` (mis. database lama). Jalankan `php artisan db:seed --class=RolesAndPermissionsSeeder` atau lihat [Menjadikan pengguna sebagai admin](#menjadikan-pengguna-sebagai-admin). |
| Registrasi error saat mengirim email | Set `MAIL_MAILER=log` untuk lokal, atau isi kredensial SMTP yang valid. |
| Video di modul tidak tampil | Video di-embed dari YouTube, sehingga membutuhkan koneksi internet. |
| `npm install` memperingatkan *install scripts blocked* (esbuild) | Aman diabaikan; build Vite tetap berjalan karena binary esbuild sudah tersedia lewat paket platform. |
| Tinker di Docker: *Writing to directory /.config/psysh is not allowed* | Tambahkan `-e HOME=/tmp` pada `docker run` (sudah termasuk dalam alias `dphp`). |
