# How to Set Up Laravel Development Environment on Windows Using Laragon

Setting up a local Laravel development environment on Windows can feel overwhelming, especially when dealing with PHP versions, databases, web servers, and frontend tooling. **Laragon** simplifies this process by providing a lightweight, portable, and developer-friendly local server environment.

Although newer tools like Laravel Herd exist, Laragon is still widely used on Windows and remains a solid choice for local Laravel development—especially for beginners and legacy projects.

This guide explains how to install and configure **Laragon with Laravel 12**, including database setup and common fixes.

---

## What Is Laragon?

Laragon is a **Windows-only local development environment** designed primarily for PHP developers. It bundles everything needed to run Laravel applications:

* PHP (multiple versions supported)
* Apache or Nginx
* MySQL / MariaDB
* Node.js
* Composer

Laragon is portable, fast, and requires almost no configuration to get started.

---

## Why Use Laragon for Laravel?

Laragon is a good choice if you:

* Are on **Windows**
* Want an **all-in-one setup**
* Prefer not to install tools separately
* Are learning Laravel
* Maintain older Laravel projects

Laragon automatically detects Laravel projects and serves them using a clean `.test` domain.

---

## Prerequisites

Before starting, ensure you have:

* Windows 10 or Windows 11
* Administrator access
* Internet connection
* Basic command prompt familiarity

No need to install PHP, MySQL, or Apache separately—Laragon handles everything.

---

## Step 1: Download and Install Laragon

1. Visit the official Laragon website
2. Download **Laragon – Full Version** (recommended)
3. Run the installer
4. Choose an installation path (default is `C:\laragon`)
5. Complete the installation and launch Laragon

Once started, Laragon runs in the system tray.

---

## Step 2: Start Laragon Services

Open Laragon and click **Start All**.

This starts:

* Web server (Apache by default)
* Database server (MySQL or MariaDB)

You should see green indicators confirming services are running.

---

## Step 3: Select PHP Version (Important for Laravel 12)

Laravel 12 requires **PHP 8.2 or higher**.

To switch PHP version:

1. Right-click Laragon tray icon
2. Go to **PHP → Version**
3. Select **PHP 8.2 or 8.3**
4. Laragon will automatically restart services

Verify PHP version:

```bash
php -v
```

---

## Step 4: Create a Laravel 12 Project

Laragon supports multiple ways to create Laravel projects.

### Option 1: Using Laravel Installer (Recommended)

First, install the Laravel installer (only once):

```bash
composer global require laravel/installer
```

Then create a project:

```bash
laravel new blog-app
```

Laravel 12’s installer will prompt you to:

* Select starter kit
* Choose database (SQLite by default)
* Configure authentication (optional)

By default, Laravel 12 uses **SQLite**, which works instantly with no setup.

---

### Option 2: Using Composer Create Project

```bash
composer create-project laravel/laravel blog-app
```

---

## Step 5: Serve the Project in Laragon

Move your project into Laragon’s web directory:

```
C:\laragon\www\blog-app
```

Laragon automatically detects it.

Visit in browser:

```
http://blog-app.test
```

No virtual host configuration required.

---

## Step 6: Database Setup (SQLite – Default)

Laravel 12 uses SQLite by default.

To enable it:

1. Open `.env`
2. Set:

```env
DB_CONNECTION=sqlite
```

3. Create the database file:

```bash
php artisan migrate
```

This is the **fastest setup** and recommended for learning or small projects.

---

## Step 7: Using MySQL with Laragon (Optional)

If you want MySQL instead of SQLite:

### Start MySQL

* Click **Start All** in Laragon
* MySQL runs automatically

### Default MySQL Credentials

```
Host: 127.0.0.1
Port: 3306
Username: root
Password: (empty)
```

### Create Database

Use Laragon → **Database** → HeidiSQL
Create a database like:

```
laravel_app
```

### Update `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_app
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

---

## Step 8: Install Frontend Dependencies (Vite)

Laravel 12 uses Vite for frontend assets.

```bash
npm install
npm run dev
```

If you want production build:

```bash
npm run build
```

---

## Common Issues and Fixes

### PHP Extension Missing

Enable extensions from:

```
Laragon → PHP → Extensions
```

Ensure these are enabled:

* pdo_mysql
* sqlite
* mbstring
* fileinfo
* openssl

Restart Laragon after changes.

---

### Vite Manifest Not Found

Run:

```bash
npm run build
```

Ensure `public/build/manifest.json` exists.

---

### Port 80 / 443 Conflict

Disable:

* IIS
* XAMPP
* Skype web ports

Or change Laragon’s port from settings.

---

### Permission Errors

Avoid installing Laragon inside:

```
C:\Program Files
```

Use:

```
C:\laragon
```

---

## Laragon vs Modern Alternatives

Laragon is stable and easy, but modern Laravel tooling favors:

* **Herd** (Windows/macOS)
* **Valet Linux** (Linux)

Laragon:

* ❌ Not Laravel-official
* ❌ No per-project PHP isolation
* ❌ Slower updates

Still, it remains perfectly usable for learning and local development.

---

## Best Practices

* Use **SQLite** unless MySQL is required
* Keep PHP updated
* Avoid mixing multiple local servers
* Use `.env` carefully
* Don’t use Laragon for production hosting

---

## Conclusion

Laragon provides one of the easiest ways to run Laravel on Windows. With minimal setup, automatic site detection, and bundled services, it removes much of the friction beginners face.

While newer tools like Laravel Herd offer a more modern experience, Laragon remains a reliable and approachable choice—especially for Windows users starting their Laravel journey.

---
