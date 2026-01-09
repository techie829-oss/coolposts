# How to Set Up Laravel Development Environment on macOS Using Herd and DBngin

Setting up a Laravel development environment on macOS traditionally involves configuring PHP, web servers, and databases manually. This process can be time-consuming and error-prone, especially when managing multiple PHP versions or database instances.

**In my experience**, switching to Laravel Herd and DBngin saves hours of configuration time compared to managing Homebrew services manually. Herd provides a native macOS interface for managing PHP versions and serving Laravel projects, while DBngin makes creating local database servers effortless.

This guide walks through the complete setup process using these tools, specifically designed for both beginners and experienced developers.

## Why Use Herd and DBngin?

**Laravel Herd** is a macOS application built specifically for Laravel development. It manages PHP versions, handles web server configuration, and serves local sites with automatic HTTPS support. No manual nginx or Apache configuration required.

**DBngin**, created by the TablePlus team, makes spinning up local MySQL, PostgreSQL, or MariaDB servers incredibly simple. You can run multiple database versions simultaneously and see connection details instantly.

Together, these tools eliminate the friction in local Laravel set up.

## Prerequisites

Before starting, ensure you have:

- macOS (Herd is macOS-only)
- **Homebrew** installed (highly recommended for managing other tools)
- **Git** for cloning repositories
- Basic terminal familiarity

## Step 1: Install Laravel Herd

Download Herd from the official Laravel Herd website and install the application.

After installation, open Herd and grant it filesystem access when prompted. You'll need to allow access to the folder where you keep your projects, such as `~/Projects` or `~/Sites`.

Open Herd preferences and navigate to the PHP section. Here you can select which PHP version to use (8.1, 8.2, 8.3, etc.). Herd manages multiple PHP versions simultaneously, making it easy to switch between projects with different requirements.

**Pro Tip:** Herd automatically configures a web server and handles local domain resolution with HTTPS support out of the box. If your browser warns about the certificate, you may need to trust the Herd CA in your Keychain, though Herd usually handles this automatically.

## Step 2: Install DBngin

Download DBngin from dbngin.com and install the application.

Open DBngin and create a new database server by selecting your preferred database engine (MySQL or PostgreSQL) and version. DBngin will start the server and display the connection details including host, port, username, and password.

**Important Note:** By default, DBngin often creates a `root` user with an **empty password**. Be sure to check the connection string it displays. If the password field is blank, leave `DB_PASSWORD` empty in your `.env` file later.

The default host is usually `127.0.0.1`, MySQL typically uses port `3306`.

Create a new database using DBngin's interface or a database management tool like TablePlus or Sequel Pro. Name it appropriately for your project, such as `laravel_local`.

## Step 3: Verify Composer and Node.js

Unlike standard PHP setups, Herd includes the PHP binary, but you still need Composer and Node.js for dependency management.

**Check if Composer is installed:**
```bash
composer --version
```
If not found, install it via Homebrew:
```bash
brew install composer
```

**Check Node.js:**
Herd does **not** manage Node.js versions. For compiling frontend assets (Vite), use a version manager like `nvm` or `fnm`.
```bash
# Install fnm (Fast Node Manager) via Homebrew
brew install fnm

# Install latest Node LTS
fnm install --lts
```

## Step 4: Create or Clone Your Laravel Project

Create a new Laravel project or clone an existing one:

```bash
# Create new project
composer create-project laravel/laravel my-project

# Or clone existing project
git clone git@github.com:username/project.git my-project
cd my-project
```

Install dependencies:

```bash
composer install
cp .env.example .env
```

## Step 5: Configure Herd to Serve Your Project

Open Herd and click the add site button. Set the project folder to your Laravel project root directory containing `composer.json`.

**Critical Setting:** Configure the document root to point to the `public` folder inside your Laravel project, not the project root itself. The path should be similar to `/Users/username/Projects/my-project/public`.

Select the appropriate PHP version for your project and start the site. Herd will assign a local URL, typically using the `.test` domain with automatic HTTPS.

## Step 6: Configure Laravel Environment

Open the `.env` file in your project and configure the database connection using the credentials from DBngin:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_local
DB_USERNAME=root
DB_PASSWORD=          # Leave empty if DBngin showed no password
```

Verify that the port matches exactly what DBngin shows for your database server. DBngin may assign non-standard ports (like 3307) if 3306 is already in use.

## Step 7: Run Laravel Setup Commands

Generate the application key and run database migrations:

```bash
php artisan key:generate
php artisan migrate
```

If you have seeders:

```bash
php artisan migrate --seed
```

## Step 8: Build Frontend Assets

For projects using Vite, install Node dependencies and build assets:

```bash
npm install
```

For development directly:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

**Common Issue:** If you see "Vite manifest not found", it means you haven't run the build command yet.

## Step 9: Verify Installation

Visit the URL provided by Herd in your browser (typically something like `https://my-project.test`). You should see your Laravel application homepage.

If everything is configured correctly, the application should load without errors and database connections should work properly.

## Common Issues and Solutions

### Database Connection Errors
If Laravel cannot connect to the database, verify that:
- DBngin database server is running (green light).
- Port number in `.env` matches DBngin exactly.
- **Password:** Double-check if DBngin set an empty password or `root`.
- Database name exists in DBngin.

### PDO Driver Not Found
Herd includes PHP extensions, but they may need to be enabled. Open Herd preferences, navigate to PHP settings, and enable `pdo_mysql` for MySQL or `pdo_pgsql` for PostgreSQL. Restart the site after enabling extensions.

### Vite Manifest Not Found
This error occurs when Laravel expects production assets but `npm run build` hasn't been executed. Either run `npm run build` to generate production assets or use `npm run dev` during development.

### File Permission Errors
If Laravel cannot write to `storage` or `bootstrap/cache` directories, you may need to adjust ownership. Be careful with `sudo` permissions. usually, running this from the project root fixes it:

```bash
chmod -R 775 storage bootstrap/cache
```

### Port Conflicts
DBngin automatically handles port conflicts by assigning alternative ports (e.g., 3307). Always use the **port shown in DBngin's interface** rather than assuming 3306.

## Quick Setup Checklist

```bash
# 1. Install Composer & Node (if missing)
brew install composer fnm

# 2. Clone project
git clone git@github.com:username/project.git
cd project

# 3. Install dependencies
composer install
cp .env.example .env

# 4. Configure environment
php artisan key:generate

# 5. Install frontend dependencies
npm install

# 6. Set up database in DBngin (Check Port & Password!)
# Update .env with DBngin credentials

# 7. Run migrations
php artisan migrate

# 8. Build assets
npm run dev
```

Configure Herd to serve `public` folder and visit the assigned URL.

## Best Practices

Keep `APP_ENV=local` and `APP_DEBUG=true` in development. These settings should be changed for production deployments.

Use DBngin for all local database needs. It isolates local databases from system databases and makes managing multiple projects straightforward.

Herd's ability to manage multiple PHP versions per site makes it ideal for maintaining legacy projects alongside modern ones.

## Conclusion

Laravel Herd and DBngin transform local Laravel development on macOS from a configuration-heavy process into a streamlined workflow. By handling the complexity of web servers, PHP versions, and database servers, these tools allow you to focus on building applications rather than managing infrastructure.

Once configured, this setup requires minimal maintenance and provides a robust, professional development environment.