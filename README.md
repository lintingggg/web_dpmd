# Deployment Web DPMD

Catatan:

- Project menggunakan Laravel 12.
    
- Production menggunakan Nginx + PHP-FPM + MySQL.
    
- Laravel Sail hanya digunakan untuk development.
    
- Panduan ini ditujukan untuk Ubuntu 24.04 LTS.
    

1. REQUIREMENT SOFTWARE
    

---

OS:  
Ubuntu 24.04 LTS

Required:  
PHP 8.4.x  
Composer 2.x  
Node.js 22.x  
npm 10.x  
MySQL 8.x  
Nginx 1.x  
Git

2. INSTALL REQUIREMENTS
    

---

INSTALL PHP 8.4:

```
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install -y \
php8.4-cli \
php8.4-common \
php8.4-fpm \
php8.4-mysql \
php8.4-mbstring \
php8.4-xml \
php8.4-curl \
php8.4-zip \
php8.4-intl \
php8.4-bcmath \
php8.4-opcache
```

Cek:

```
php -v
```

INSTALL COMPOSER:

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
```

Cek:

```
composer --version
```

INSTALL NODE.JS 22:

```
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
```

Cek:

```
node -v
npm -v
```

INSTALL MYSQL:

```
sudo apt install -y mysql-server mysql-client

sudo systemctl enable mysql
sudo systemctl start mysql
```

Cek:

```
mysql --version
```

INSTALL NGINX:

```
sudo apt install -y nginx

sudo systemctl enable nginx
sudo systemctl start nginx
```

## 3. AKTIFKAN PHP-FPM

```
sudo systemctl enable php8.4-fpm
sudo systemctl start php8.4-fpm
```

Cek:

```
sudo systemctl status php8.4-fpm
```

Pastikan socket tersedia:

```
ls -lah /run/php/php8.4-fpm.sock
```

## 4. BUAT DATABASE

Masuk MySQL:

```
sudo mysql
```

Buat database:

```
CREATE DATABASE db_dpmd
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Buat user:

```
CREATE USER 'admin_dpmd'@'localhost'
IDENTIFIED BY 'PASSWORD_DATABASE';
```

Berikan akses:

```
GRANT ALL PRIVILEGES ON db_dpmd.* TO 'admin_dpmd'@'localhost';

FLUSH PRIVILEGES;
```

Keluar:

```
EXIT;
```

## 5. CLONE REPOSITORY

Masuk ke folder:

```
cd /var/www
```

Clone:

```
git clone https://github.com/lintingggg/web_dpmd.git
```

Masuk project:

```
cd web_dpmd
```

Lihat branch:

```
git branch -a
```

Gunakan branch yang ingin di-deploy.

Contoh:

```
git checkout -b feat/fe-beranda origin/feat/fe-beranda
```

## 6. KONFIGURASI .ENV

Copy:

```
cp .env.example .env
```

Edit:

```
nano .env
```

Konfigurasi utama:

```
APP_NAME="DPMD Bangkalan"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP-VPS
```

Database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_dpmd
DB_USERNAME=admin_dpmd
DB_PASSWORD=PASSWORD_DATABASE
```

SMTP:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=GOOGLE_APP_PASSWORD
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

RECAPTCHA:

```
RECAPTCHA_SITE_KEY=YOUR_SITE_KEY
RECAPTCHA_SECRET_KEY=YOUR_SECRET_KEY
```

Gunakan key reCAPTCHA yang sesuai dengan domain/IP deployment.

JANGAN menggunakan password Gmail utama.  
Gunakan Google App Password.

JANGAN commit `.env` ke Git.

7. INSTALL DEPENDENCY PHP
    

---

```
composer install --no-dev --optimize-autoloader
```

## 8. GENERATE APP KEY

```
php artisan key:generate
```

## 9. MIGRATE + SEED DATABASE

Untuk VPS baru:

```
php artisan migrate --seed --force
```

Seeder project akan membuat data awal, termasuk user admin jika memang tersedia pada seeder.

Jika migration dan seeding sudah pernah dilakukan, jangan menjalankan seeder sembarangan karena dapat mengubah data existing.

10. STORAGE LINK
    

---

```
php artisan storage:link
```

## 11. PERMISSION LARAVEL

```
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## 12. INSTALL NPM DEPENDENCY

Gunakan `npm ci` karena project memiliki `package-lock.json`:

```
npm ci
```

## 13. BUILD FRONTEND

```
npm run build
```

Jika VPS 1 GB mengalami JavaScript heap out of memory:

```
NODE_OPTIONS="--max-old-space-size=1024" npm run build
```

Pastikan VPS sudah memiliki SWAP.

14. OPTIMIZE LARAVEL
    

---

```
php artisan optimize:clear

php artisan config:cache
php artisan view:cache
```

Jika route caching aman digunakan:

```
php artisan route:cache
```

## 15. KONFIGURASI NGINX

Buat:

```
sudo nano /etc/nginx/sites-available/web_dpmd
```

Isi:

```
server {
    listen 80;
    listen [::]:80;

    server_name _;

    root /var/www/web_dpmd/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan:

```
sudo ln -s /etc/nginx/sites-available/web_dpmd /etc/nginx/sites-enabled/web_dpmd
```

Jika perlu hapus default:

```
sudo rm /etc/nginx/sites-enabled/default
```

Test:

```
sudo nginx -t
```

Reload:

```
sudo systemctl reload nginx
```

=============================================================  