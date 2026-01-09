# How to Set Up Laravel 12 Development Environment on Linux Using Valet Linux

While macOS and Windows developers have **Laravel Herd**, there is currently **no official Herd release for Linux**.
However, Linux developers can achieve a **nearly identical native Laravel experience** using **Valet Linux**.

Valet Linux works across **most modern Linux distributions**, especially those commonly used by developers.

---

## Supported Linux Distributions

Valet Linux works best on **Debian-based and Ubuntu-based systems**, and also supports several Arch-based distributions with minor adjustments.

### Debian / Ubuntu Family (Best Supported)

* **Ubuntu** (20.04, 22.04, 24.04)
* **Linux Mint**
* **Pop!_OS**
* **Elementary OS**
* **Kubuntu / Xubuntu / Lubuntu**
* **Debian 11 / 12**

These distributions are **fully compatible** with Valet Linux using the steps in this guide.

### Arch-Based Distributions (Works with Adjustments)

* **Manjaro**
* **Arch Linux**

> On Arch-based systems, package names and PHP installation steps may differ slightly, but Valet Linux functions the same once PHP, Nginx, and Dnsmasq are installed.

### Fedora / RHEL-Based (Advanced Users)

* **Fedora**
* **Rocky Linux**
* **AlmaLinux**

Valet Linux can run on these systems, but manual dependency handling is required. This guide focuses on Debian/Ubuntu-based systems for reliability.

---

## Why Valet Linux Is the Best Herd Alternative on Linux

Valet Linux mirrors the same philosophy as Herd:

* Native OS performance (no Docker, no VM)
* Nginx-based web serving
* Automatic `.test` domains
* Minimal configuration
* Extremely fast for Laravel projects

For Linux developers who prefer **lightweight, system-level tooling**, Valet Linux is the closest match to Herd.

---

## Prerequisites

* Any supported Linux distribution listed above
* `sudo` access
* Basic terminal familiarity

---

## PHP & Laravel 12 Compatibility

Laravel 12 officially supports:

* **PHP 8.2**
* **PHP 8.3**

This guide uses **PHP 8.3**, which works across Ubuntu, Mint, Debian, and Manjaro (with distro-specific package names).

---

## Database Note (Important for Laravel 12)

Laravel 12 uses **SQLite by default**, which means:

* No database server is required initially
* Ideal for local development
* Zero configuration friction

If you want MySQL for production parity, you can install **MariaDB** later (covered in this guide).

---

## When to Use MySQL vs SQLite

| Use Case                 | Recommended DB  |
| ------------------------ | --------------- |
| Local dev & testing      | SQLite          |
| Production parity        | MySQL / MariaDB |
| CI / automation          | SQLite          |
| Heavy relational queries | MySQL           |

SQLite is **not a limitation** for most local Laravel workflows.

---

## Step 1: Update System & Install Core Tools

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git unzip software-properties-common
```

---

## Step 2: Install PHP (Laravel 12 Compatible)

Laravel 12 officially supports **PHP 8.2 and 8.3**. Install PHP 8.3 using the Ondřej Surý PPA (Ubuntu/Debian).

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

This automatically configures Nginx, Dnsmasq, and required permissions.

---

## Step 5: Laravel 12 Default Database (SQLite)

Laravel 12 **uses SQLite by default**, which is recommended for local development.

Create your projects directory:

```bash
mkdir ~/Projects
cd ~/Projects
valet park
```

---

## Step 6: Create a Laravel 12 Project

Use the Laravel installer (preferred):

```bash
composer global require laravel/installer
laravel new cool-app
```

During setup, choose **SQLite**.

Move into the project:

```bash
cd cool-app
touch database/database.sqlite
php artisan migrate
```

Visit `http://cool-app.test`.

---

## Step 7: When You Need MySQL (Optional)

If you want production parity, install MariaDB:

```bash
sudo apt install -y mariadb-server
sudo systemctl enable mariadb
sudo systemctl start mariadb
```

Create database:

```bash
sudo mysql
-- inside mysql shell:
CREATE DATABASE laravel_local;
EXIT;
```

Update `.env` for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_local
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

---

## Step 8: Enable HTTPS (Optional)

To enable trusted HTTPS:

```bash
valet secure
```

Your site becomes `https://cool-app.test`.

---

## Common Issues & Fixes

### Site Not Loading
`valet restart`

### DNS Issues
`sudo systemctl restart dnsmasq`

### Permission Errors
```bash
sudo chown -R $USER:$USER ~/Projects
chmod -R 775 storage bootstrap/cache
```

---

## Summary

* Linux **does not have Herd**, but Valet Linux provides the same workflow
* Works on **Ubuntu, Mint, Pop!_OS, Debian, Manjaro**
* Laravel 12 defaults to **SQLite**
* MySQL is optional and easy to add
* Native performance without Docker complexity

---
