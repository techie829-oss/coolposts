# How to Set Up Laravel Development Environment on macOS Using Herd and DBngin

Setting up a Laravel development environment on macOS traditionally involves configuring PHP, web servers, and databases manually. This process can be time-consuming and error-prone, especially when managing multiple PHP versions or database instances.

Laravel Herd and DBngin simplify this workflow significantly. Herd provides a native macOS interface for managing PHP versions and serving Laravel projects, while DBngin makes creating local database servers effortless.

This guide walks through the complete setup process using these tools.

## Why Use Herd and DBngin?

**Laravel Herd** is a macOS application built specifically for Laravel development. It manages PHP versions, handles web server configuration, and serves local sites with automatic HTTPS support. No manual nginx or Apache configuration required.

**DBngin**, created by the TablePlus team, makes spinning up local MySQL, PostgreSQL, or MariaDB servers incredibly simple. You can run multiple database versions simultaneously and see connection details instantly.

Together, these tools eliminate most of the friction in local Laravel development.

## Prerequisites

Before starting, ensure you have:

- macOS (Herd is macOS-only)
- Homebrew installed (optional but recommended)
- Git for cloning repositories
- Basic terminal familiarity

## Step 1: Install Laravel Herd

Download Herd from the official Laravel Herd website and install the application.

After installation, open Herd and grant it filesystem access when prompted. You'll need to allow access to the folder where you keep your projects, such as `~/Projects` or `~/Sites`.

Open Herd preferences and navigate to the PHP section. Here you can select which PHP version to use (8.1, 8.2, 8.3, etc.). Herd manages multiple PHP versions simultaneously, making it easy to switch between projects with different requirements.

Herd automatically configures a web server and handles local domain resolution with HTTPS support out of the box.

## Step 2: Install DBngin

Download DBngin from dbngin.com and install the application.

Open DBngin and create a new database server by selecting your preferred database engine (MySQL or PostgreSQL) and version. DBngin will start the server and display the connection details including host, port, username, and password.

The default host is usually `127.0.0.1`, MySQL typically uses port `3306`, and the default username is `root`. Note these credentials as you'll need them for your Laravel environment configuration.

Create a new database using DBngin's interface or a database management tool like TablePlus or Sequel Pro. Name it appropriately for your project, such as `laravel_local`.

## Step 3: Create or Clone Your Laravel Project

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

## Step 4: Configure Herd to Serve Your Project

Open Herd and click the add site button. Set the project folder to your Laravel project root directory containing `composer.json`.

**Critical setting**: Configure the document root to point to the `public` folder inside your Laravel project, not the project root itself. The path should be similar to `/Users/username/Projects/my-project/public`.

Select the appropriate PHP version for your project and start the site. Herd will assign a local URL, typically using the `.test` domain with automatic HTTPS.

## Step 5: Configure Laravel Environment

Open the `.env` file in your project and configure the database connection using the credentials from DBngin:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_local
DB_USERNAME=root
DB_PASSWORD=your_password
```

Verify that the port matches exactly what DBngin shows for your database server. DBngin may assign non-standard ports if the default is already in use.

## Step 6: Run Laravel Setup Commands

Generate the application key and run database migrations:

```bash
php artisan key:generate
php artisan migrate
```

If you have seeders:

```bash
php artisan migrate --seed
```

## Step 7: Build Frontend Assets

For projects using Vite, install Node dependencies and build assets:

```bash
npm install
```

For development with hot module replacement:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

## Step 8: Verify Installation

Visit the URL provided by Herd in your browser (typically something like `https://my-project.test`). You should see your Laravel application homepage.

If everything is configured correctly, the application should load without errors and database connections should work properly.

## Common Issues and Solutions

### Database Connection Errors

If Laravel cannot connect to the database, verify that:

- DBngin database server is running
- Port number in `.env` matches DBngin exactly
- Database name exists in DBngin
- Username and password are correct

### PDO Driver Not Found

Herd includes PHP extensions, but they may need to be enabled. Open Herd preferences, navigate to PHP settings, and enable `pdo_mysql` for MySQL or `pdo_pgsql` for PostgreSQL. Restart the site after enabling extensions.

### Vite Manifest Not Found

This error occurs when Laravel expects production assets but `npm run build` hasn't been executed. Either run `npm run build` to generate production assets or use `npm run dev` during development.

Ensure the `public/build` directory exists and contains `manifest.json` for production environments.

### File Permission Errors

If Laravel cannot write to `storage` or `bootstrap/cache` directories:

```bash
sudo chown -R $(whoami) storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Port Conflicts

DBngin automatically handles port conflicts by assigning alternative ports. Always use the port shown in DBngin's interface rather than assuming the default.

## Quick Setup Checklist

```bash
# Clone project
git clone git@github.com:username/project.git
cd project

# Install dependencies
composer install
cp .env.example .env

# Configure environment
php artisan key:generate

# Install frontend dependencies
npm install

# Set up database in DBngin
# Update .env with DBngin credentials

# Run migrations
php artisan migrate

# Build assets
npm run dev
```

Configure Herd to serve `public` folder and visit the assigned URL.

## Best Practices

Keep `APP_ENV=local` and `APP_DEBUG=true` in development. These settings should be changed for production deployments.

Use DBngin for all local database needs. It isolates local databases from system databases and makes managing multiple projects straightforward.

Herd's ability to manage multiple PHP versions per site makes it ideal for maintaining legacy projects alongside modern ones.

For sharing local sites externally during testing, Herd includes a tunneling feature. However, use proper hosting for production applications.

## Conclusion

Laravel Herd and DBngin transform local Laravel development on macOS from a configuration-heavy process into a streamlined workflow. These tools handle the complexity of web servers, PHP versions, and database servers, allowing developers to focus on building applications rather than managing infrastructure.

Once configured, this setup requires minimal maintenance and provides a consistent development environment across projects.