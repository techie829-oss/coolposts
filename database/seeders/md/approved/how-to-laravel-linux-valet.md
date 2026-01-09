# How to Set Up Laravel 12 Development Environment on Linux Using Valet Linux

Unlike macOS and Windows, **Laravel Herd is not available for Linux**. However, Linux developers can still achieve a **very similar, native, high-performance local development experience** using **Valet Linux**.

Valet Linux provides automatic Nginx configuration, local `.test` domains, and optional HTTPS — without Docker, virtual machines, or heavy stacks. When paired with Laravel 12’s defaults (SQLite first, MySQL only when needed), this setup is fast, stable, and close to production behavior.

This guide explains the **correct, modern way** to run Laravel 12 locally on Linux.

---

## Why Valet Linux Is the Best Herd Alternative on Linux

Valet Linux is a community-maintained Linux port of Laravel Valet. It mirrors the same philosophy used by Herd:

* Runs **natively** on your OS (no Docker overhead)
* Uses **Nginx**, not Apache
* Auto-serves projects using `.test` domains
* Minimal configuration
* Extremely fast file access

This makes it ideal for Laravel development, especially on Ubuntu and Debian systems.

---

## Supported Systems

* Ubuntu 22.04 LTS / 24.04 LTS
* Debian 11 / 12
* Fedora (with minor adjustments)

> This guide assumes **Ubuntu/Debian**, which is the most common and best-supported path.

---

## Prerequisites

* `sudo` access
* Basic terminal knowledge
* Internet connection

---

## Step 1: Update System & Install Core Tools

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git unzip software-properties-common
```

---

## Step 2: Install PHP (Laravel 12 Compatible)

Laravel 12 officially supports **PHP 8.2 and 8.3**. Install PHP 8.3 using the Ondřej Surý PPA.

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

Install PHP and required extensions:

```bash
sudo apt install -y \
php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-mbstring \
php8.3-xml php8.3-zip php8.3-sqlite3 php8.3-bcmath php8.3-gd
```

Verify PHP:

```bash
php -v
```

---

## Step 3: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Verify:

```bash
composer --version
```

---

## Step 4: Install Valet Linux

Install Valet Linux globally using Composer:

```bash
composer global require cpriego/valet-linux
```

Add Composer global binaries to PATH:

```bash
echo 'export PATH="$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

Install Valet:

```bash
valet install
```

This automatically configures:

* Nginx
* Dnsmasq
* Required permissions

---

## Step 5: Laravel 12 Default Database (SQLite)

Laravel 12 **uses SQLite by default**, which is recommended for local development.

Create your projects directory:

```bash
mkdir ~/Projects
cd ~/Projects
```

Tell Valet to serve this directory:

```bash
valet park
```

---

## Step 6: Create a Laravel 12 Project (Recommended Method)

Use the Laravel installer (preferred):

```bash
composer global require laravel/installer
laravel new cool-app
```

During setup:

* Choose SQLite
* Skip extra services if not needed

Move into the project:

```bash
cd cool-app
```

Create SQLite database file if missing:

```bash
touch database/database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

Visit:

```
http://cool-app.test
```

Your app should load instantly.

---

## Step 7: When You Need MySQL (Optional)

Laravel 12 does **not require MySQL** locally, but if you want production parity, install MariaDB.

```bash
sudo apt install -y mariadb-server
sudo systemctl enable mariadb
sudo systemctl start mariadb
```

By default, local MariaDB often runs **without a password**, which is fine for development.

Create database:

```bash
sudo mysql
```

```sql
CREATE DATABASE laravel_local;
EXIT;
```

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_local
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations again:

```bash
php artisan migrate
```

---

## Step 8: Enable HTTPS (Optional)

To enable trusted HTTPS:

```bash
valet secure
```

Your site becomes:

```
https://cool-app.test
```

---

## Common Issues & Fixes

### Site Not Loading

```bash
valet restart
```

### DNS Issues

```bash
sudo systemctl restart dnsmasq
```

### Permission Errors

```bash
sudo chown -R $USER:$USER ~/Projects
chmod -R 775 storage bootstrap/cache
```

### PHP Version Conflicts

Ensure only one PHP version is active:

```bash
php -v
```

---

## Recommended Linux Laravel Workflow

```bash
laravel new app-name
cd app-name
php artisan migrate
npm install
npm run dev
```

No Docker. No Apache. No VM.

---

## Herd vs Valet Linux (Reality Check)

| Feature           | Herd (macOS/Windows) | Valet Linux |
| ----------------- | -------------------- | ----------- |
| Native speed      | ✅                    | ✅           |
| Docker-free       | ✅                    | ✅           |
| Automatic domains | ✅                    | ✅           |
| GUI               | ✅                    | ❌           |
| Linux support     | ❌                    | ✅           |

Valet Linux is the **closest functional equivalent** to Herd on Linux.

---

## Conclusion

For Linux developers, **Valet Linux + Laravel 12** is the cleanest and fastest local development setup available today. SQLite handles most local needs, MySQL is optional, and Valet keeps your system lightweight and responsive.

If Herd ever arrives on Linux, migration will be trivial — because this workflow already matches Laravel’s modern philosophy.

---
