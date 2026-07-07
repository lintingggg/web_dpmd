PANDUAN CLONE & RUN PROJECT WEB DPMD (WINDOWS + DOCKER WSL 2)
=============================================================

Syarat Wajib Sebelum Mulai:
1. Docker Desktop sudah terinstal dan dalam keadaan RUNNING.
2. Fitur WSL 2 Integration di Docker Desktop sudah diaktifkan (Settings > Resources > WSL Integration).
3. Buka terminal WSL (Ubuntu) atau terminal VS Code yang sudah terhubung ke WSL. JANGAN gunakan Command Prompt (CMD) biasa.

Langkah-Langkah Instalasi:

1. Clone Repositori
Jalankan perintah ini di terminal WSL untuk mengunduh kode dari GitHub:
git clone https://github.com/username-kalian/web_dpmd.git

2. Masuk ke Folder Project
cd web_dpmd

3. Siapkan File Konfigurasi (.env)
Copy file pengaturan bawaan menjadi file .env utama:
cp .env.example .env

4. Install Dependency PHP (Backend) via Docker
Karena ini project baru di-clone, folder "vendor" belum ada. Jalankan perintah ini untuk menginstal package Laravel tanpa perlu menginstal PHP di laptop lokal:
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

5. Nyalakan Mesin Docker (Sail)
Setelah proses instalasi di atas selesai, nyalakan container database dan server lokal:
./vendor/bin/sail up -d

(Tunggu beberapa saat sampai proses download image dan booting selesai)

6. Setup Aplikasi Laravel
Generate kunci keamanan aplikasi dan buat struktur tabel database (pastikan container sudah menyala):
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate

7. Install Dependency Node.js (Frontend)
Unduh package Vue, Tailwind, dan INA Digital Design System:
./vendor/bin/sail npm install

8. Jalankan Server Frontend (Vite)
./vendor/bin/sail npm run dev

=============================================================
SELESAI!
Akses website utama: http://localhost
Akses database (phpMyAdmin): http://localhost:8080