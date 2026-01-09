# How to Set Up Laravel 12 Development Environment on macOS Using Herd and DBngin

Setting up a Laravel development environment on macOS used to mean manually installing PHP, configuring a web server, managing PHP versions, and setting up databases. While powerful, this approach often led to version conflicts, broken extensions, and wasted setup time.

With **Laravel Herd**, **Laravel Installer**, and **DBngin**, local development on macOS is now much simpler and closer to how modern Laravel is intended to be used — especially with **Laravel 12**, which ships with SQLite by default.

This guide explains the **correct, real-world setup** for Laravel 12 on macOS.

---

## Why This Setup Works Best for Laravel 12

Laravel 12 introduces a more opinionated local setup:

* SQLite is the **default database**
* Laravel Installer offers **interactive project setup**
* Herd handles PHP, HTTPS, and local domains automatically
* DBngin provides MySQL or PostgreSQL only when you actually need them

This avoids unnecessary services running in the background and keeps local environments clean.

---

## Tools Used

### Laravel Herd (macOS)

* Manages PHP versions (8.2, 8.3)
* Serves Laravel apps automatically
* Provides HTTPS and `.test` domains
* No Apache or nginx configuration required

### Laravel Installer

* Creates new Laravel projects
* Interactive setup (stack, testing, database)
* Recommended way to start Laravel 12 projects

### DBngin

* Runs MySQL or PostgreSQL locally
* No system-wide database installation
* MySQL defaults to **no password**
* Easy port management

---

## Prerequisites

Before starting, ensure you have:

* macOS (Apple Silicon or Intel)
* Git
* Composer
* Node.js (for Vite)
* Basic terminal knowledge

> Homebrew is optional but useful.

---

## Step 1: Install Laravel Herd

Download and install Herd for macOS.

After installation:

* Launch Herd
* Grant folder access (for `~/Sites` or `~/Projects`)
* Open Herd settings and confirm PHP version (8.3 recommended for Laravel 12)

Herd automatically:

* Configures the web server
* Enables HTTPS
* Handles local DNS resolution

No manual configuration is required.

---

## Step 2: Install Laravel Installer (Recommended)

Laravel 12 is best created using the Laravel installer.

Install it globally:

```bash
composer global require laravel/installer
```

Make sure Composer’s global bin directory is in your PATH.

Verify installation:

```bash
laravel --version
```

---

## Step 3: Create a Laravel 12 Project (Interactive)

Create a new project using:

```bash
laravel new my-project
```

During setup, Laravel will prompt you to choose:

* Starter kit (optional)
* Testing framework
* Database (SQLite is default)

✅ **Choose SQLite initially** — this is Laravel 12’s default and works out of the box.

Navigate into the project:

```bash
cd my-project
```

---

## Step 4: Serve the Project with Herd

Open Herd and add a new site:

* Select your project directory
* Herd automatically detects the `public` folder
* Choose the PHP version if prompted

Herd assigns a local URL like:

```
https://my-project.test
```

Open it in your browser — Laravel should load immediately.

---

## Step 5: SQLite (Default Laravel 12 Setup)

Laravel 12 ships with SQLite configured.

Your `.env` file will look like:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

Create the SQLite file if it doesn’t exist:

```bash
touch database/database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

At this point, **your Laravel app is fully functional** without any database server.

---

## Step 6: When You Need MySQL → Install DBngin

If your project requires MySQL (for production parity or specific features), install DBngin.

After installation:

1. Open DBngin
2. Create a new MySQL server
3. Note the **port number** (often not 3306)
4. Default credentials:

   * Username: `root`
   * Password: **empty**

Create a database (for example `laravel_local`).

---

## Step 7: Configure Laravel for MySQL (DBngin)

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306   # use the exact port shown in DBngin
DB_DATABASE=laravel_local
DB_USERNAME=root
DB_PASSWORD=
```

⚠️ Important:

* DBngin MySQL uses **no password by default**
* Always copy the port from DBngin

Run migrations again:

```bash
php artisan migrate
```

---

## Step 8: Frontend Assets (Vite)

Install Node dependencies:

```bash
npm install
```

For development:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

If Laravel complains about missing `manifest.json`, it means `npm run build` hasn’t been executed.

---

## Common Issues and Fixes

### Database Connection Refused

* Ensure DBngin server is running
* Verify port number
* Confirm database exists
* Leave `DB_PASSWORD` empty

### PDO Driver Missing

Enable extensions in Herd:

* `pdo_mysql` for MySQL
* Restart the site after changes

### Vite Assets Not Loading

Run:

```bash
npm run build
```

Ensure `public/build/manifest.json` exists.

### Permission Errors

Fix directory permissions:

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Recommended Workflow Summary

```bash
laravel new project-name
cd project-name
php artisan migrate
npm install
npm run dev
```

Optional:

* Install DBngin only if MySQL is required
* Switch `.env` from SQLite to MySQL

---

## Best Practices for macOS Laravel Development

* Use **SQLite by default** for speed
* Add DBngin only when needed
* Keep `APP_ENV=local`
* Keep `APP_DEBUG=true`
* Let Herd manage PHP and HTTPS
* Avoid system-wide MySQL installations

---

## Conclusion

Laravel 12, combined with Herd and DBngin, represents the cleanest local development setup Laravel has ever offered on macOS. SQLite removes unnecessary friction, Herd eliminates web server configuration, and DBngin provides databases only when required.

This setup mirrors real production workflows while staying fast, simple, and reliable for daily development.
