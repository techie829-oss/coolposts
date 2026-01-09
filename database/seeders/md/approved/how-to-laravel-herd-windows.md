# How to Set Up Laravel Development Environment on Windows Using Herd

Setting up a Laravel development environment on Windows has traditionally required installing and configuring multiple tools separately. Managing PHP versions, web servers, and databases often leads to configuration conflicts and compatibility issues.

Laravel Herd for Windows simplifies this entire process. It provides a native Windows application that manages PHP versions, serves Laravel projects, and includes built-in database support, eliminating the need for complex manual configuration.

This guide walks through the complete setup process using Herd on Windows.

## Why Use Herd for Windows?

Laravel Herd is specifically designed for Laravel development. It handles PHP version management, web server configuration, and local site serving with automatic HTTPS support. The Windows version includes everything needed to get started quickly.

Unlike traditional XAMPP or WAMP setups, Herd focuses specifically on modern Laravel development workflows and requires minimal configuration. It automatically detects Laravel projects and configures them correctly.

This focused approach eliminates the complexity of managing Apache, nginx, or IIS configurations manually.

## Prerequisites

Before starting, ensure you have:

- Windows 10 or Windows 11
- Administrator access on your machine
- Git for Windows (for cloning repositories)
- Basic command prompt or PowerShell familiarity

## Step 1: Install Laravel Herd

Download Herd for Windows from the official Laravel Herd website. The installer handles all dependencies automatically, including PHP and required extensions.

Run the installer and follow the installation prompts. Herd will install to your user directory and add itself to the Windows startup applications.

After installation completes, launch Herd from the Start menu. The application runs in the system tray and provides a quick-access menu for managing sites and settings.

Open Herd settings to configure your preferred PHP version. Herd for Windows supports multiple PHP versions (8.1, 8.2, 8.3) and allows switching between them per project.

## Step 2: Configure Database

Herd for Windows includes built-in database support. Open Herd settings and navigate to the Database section.

Enable the built-in MySQL or PostgreSQL server. Herd will start the database service and display connection credentials including host, port, username, and password.

The default configuration typically uses:
- Host: `127.0.0.1`
- Port: `3306` for MySQL or `5432` for PostgreSQL
- Username: `root` or `herd`
- Password: displayed in Herd settings

You can create databases directly through Herd's database management interface or use external tools like TablePlus, HeidiSQL, or MySQL Workbench.

Create a database for your Laravel project using your preferred database management tool.

## Step 3: Create or Clone Your Laravel Project

Open Command Prompt or PowerShell and navigate to your projects directory:

```bash
# Navigate to projects directory
cd C:\Users\YourUsername\Projects

# Create new Laravel project
composer create-project laravel/laravel my-project

# Or clone existing project
git clone https://github.com/username/project.git my-project
cd my-project
```

Install dependencies:

```bash
composer install
copy .env.example .env
```

## Step 4: Add Site to Herd

Open Herd and click the add site button. Navigate to your Laravel project directory and select the folder containing `composer.json`.

**Important**: Herd automatically detects the `public` folder as the document root for Laravel projects. You don't need to manually configure this unless your project structure is non-standard.

Select the PHP version your project requires. Herd will configure the site and assign a local URL, typically using the `.test` domain with automatic HTTPS support.

Start the site. Herd displays the URL in the sites list, usually formatted as `my-project.test`.

## Step 5: Configure Laravel Environment

Open the `.env` file in your project and configure the database connection using the credentials from Herd:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_project_db
DB_USERNAME=root
DB_PASSWORD=herd_password
```

Replace the database name and credentials with the values shown in Herd's database settings.

## Step 6: Run Laravel Setup Commands

Open Command Prompt or PowerShell in your project directory and run the setup commands:

```bash
php artisan key:generate
php artisan migrate
```

If your project includes seeders:

```bash
php artisan migrate --seed
```

## Step 7: Build Frontend Assets

For projects using Vite, install Node dependencies and build assets:

```bash
npm install
```

For development with hot reload:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

## Step 8: Verify Installation

Click the site URL in Herd or visit it in your browser (for example, `https://my-project.test`). You should see your Laravel application homepage.

If the database is configured correctly, the application should display without connection errors.

## Common Issues and Solutions

### Database Connection Failures

If Laravel cannot connect to the database:

- Verify the database server is running in Herd settings
- Confirm the port number matches Herd's configuration
- Check that the database exists
- Verify username and password are correct
- Ensure Windows Firewall isn't blocking the database port

### PHP Extension Missing

Herd includes most common PHP extensions, but if you encounter missing extension errors, check Herd's PHP settings to enable required extensions like `pdo_mysql`, `pdo_pgsql`, `mbstring`, or `fileinfo`.

### Vite Manifest Not Found

This error appears when Laravel expects production assets but they haven't been built. Run `npm run build` to generate production assets or use `npm run dev` during development.

Verify that the `public/build` directory exists and contains `manifest.json`.

### Permission Errors

Windows file permissions work differently than Unix systems. If you encounter permission errors in `storage` or `bootstrap/cache`:

Ensure your user account has full control of the project directory. Right-click the project folder, select Properties, go to Security tab, and verify your user has full control permissions.

### Port Conflicts

If another application is using port 80 or 443, Herd cannot serve sites on those ports. Stop conflicting services like IIS, XAMPP, or other web servers before starting Herd.

Check which process is using a port:

```bash
netstat -ano | findstr :80
```

## Quick Setup Checklist

```bash
# Clone or create project
git clone https://github.com/username/project.git
cd project

# Install dependencies
composer install
copy .env.example .env

# Configure environment
php artisan key:generate

# Install Node dependencies
npm install

# Enable database in Herd settings
# Create database via Herd or database tool
# Update .env with Herd database credentials

# Run migrations
php artisan migrate

# Build assets
npm run dev
```

Add site in Herd interface and visit the assigned URL.

## Best Practices

Keep `APP_ENV=local` and `APP_DEBUG=true` during local development. Change these settings appropriately before deploying to production.

Use Herd's built-in database server for local development. It's optimized for development workflows and automatically starts with Herd.

Herd's PHP version switching makes it easy to test projects with different PHP requirements. Use this feature to ensure compatibility before deploying.

For sharing local sites externally during testing, Herd includes a share feature. However, use proper hosting services for production applications.

## Herd vs Traditional Windows Setups

Traditional Windows development environments like XAMPP, WAMP, or manual installations require configuring virtual hosts, editing configuration files, and managing services manually.

Herd eliminates these complexities by providing a Laravel-focused interface that handles configuration automatically. Sites are detected, configured, and served without editing `.conf` files or managing virtual hosts.

The built-in database server means no separate MySQL or PostgreSQL installation and configuration is needed. Everything works out of the box.

## Conclusion

Laravel Herd transforms Windows Laravel development from a configuration-intensive process into a streamlined workflow. The unified interface for managing PHP versions, sites, and databases reduces setup friction significantly.

Once configured, Herd requires minimal maintenance and provides a consistent development environment that closely mirrors modern production setups. This consistency reduces deployment issues and makes local development more productive.

For Windows developers working with Laravel, Herd is the most straightforward path to a fully functional local development environment.
