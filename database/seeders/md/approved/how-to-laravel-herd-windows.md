# How to Set Up Laravel Development Environment on Windows Using Herd

Setting up a Laravel development environment on Windows has traditionally required installing and configuring multiple tools separately. Managing PHP versions, web servers, and databases often leads to configuration conflicts and compatibility issues.

Laravel Herd for Windows simplifies this entire process. It provides a native Windows application that manages PHP versions, serves Laravel projects, and aims to be a zero-configuration environment.

**In my experience**, Herd is significantly faster and cleaner than XAMPP or WSL for standard local development, but there are a few Windows-specific quirks you need to be aware of. this guide covers the complete setup, including the critical steps often missed by beginners.

## Why Use Herd for Windows?

Laravel Herd is specifically designed for Laravel development. It handles PHP version management, web server configuration, and local site serving with automatic HTTPS support. The Windows version includes everything needed to get started quickly.

Unlike traditional XAMPP or WAMP setups, Herd focuses specifically on modern Laravel development workflows and requires minimal configuration. It automatically detects Laravel projects and configures them correctly.

## Prerequisites

Before starting, ensure you have:

- Windows 10 or Windows 11
- Administrator access on your machine
- **Git for Windows** (essential for cloning repositories)
- Basic command prompt or PowerShell familiarity

## Step 1: Install Laravel Herd

Download Herd for Windows from the official Laravel Herd website. The installer handles all dependencies automatically, including PHP and required extensions.

Run the installer and follow the installation prompts. Herd will install to your user directory and add itself to the Windows startup applications.

After installation completes, launch Herd from the Start menu. The application runs in the system tray and provides a quick-access menu for managing sites and settings.

Open Herd settings to configure your preferred PHP version. Herd for Windows supports multiple PHP versions (8.1, 8.2, 8.3) and allows switching between them per project.

## Step 2: Install Dependencies (Composer & Node.js)

Herd handles PHP, but you still need other standard tools that aren't always included by default on Windows.

**1. Composer (PHP Dependency Manager)**
Herd includes a composer binary, but it's best to verify it's accessible globally:
```bash
composer --version
```
If not found, download the official **Composer-Setup.exe** for Windows.

**2. Node.js (Frontend Assets)**
Herd does **not** manage Node.js. You need this for Vite (`npm run dev`).
Download the **Node.js LTS installer** from nodejs.org or use `nvm-windows` (recommended).

## Step 3: Configure Database

**Important:** Herd for Windows offers built-in database support, but features may vary between Free and Pro versions.

- **Herd Pro:** Includes fully managed MySQL/PostgreSQL instances.
- **Herd Free:** You may need to install standard MySQL or use DBngin for Windows if it becomes available (or use the standalone MySQL installer).

If using Herd's built-in database (or MySQL installed separately):
- **Host:** `127.0.0.1`
- **Port:** `3306` (MySQL)
- **Username:** `root`
- **Password:** Often empty, or `root` depending on your installation choice.

**Pro Tip:** Always check the "Database" tab in Herd settings to see the exact credentials it is using.

## Step 4: Create or Clone Your Laravel Project

Open PowerShell or Command Prompt and navigate to your projects directory:

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

## Step 5: Add Site to Herd

Open Herd and click the "Add site" button (or the "+" icon). Navigate to your Laravel project directory and select the folder containing `composer.json`.

**Important**: Herd automatically detects the `public` folder as the document root for Laravel projects. You don't need to manually configure this unless your project structure is non-standard.

Select the PHP version your project requires. Herd will configure the site and assign a local URL, typically using the `.test` domain (e.g., `my-project.test`).

## Step 6: Configure Laravel Environment

Open the `.env` file in your project and configure the database connection.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_project_db
DB_USERNAME=root
DB_PASSWORD=          # Check Herd settings!
```

**Common Pitfall:** If using standard MySQL, the password might be empty. If using Herd Pro's managed DB, check the specific credentials provided in the settings.

## Step 7: Run Laravel Setup Commands

Open Command Prompt or PowerShell in your project directory and run the setup commands:

```bash
php artisan key:generate
php artisan migrate
```

If your project includes seeders:

```bash
php artisan migrate --seed
```

## Step 8: Build Frontend Assets

For projects using Vite, verify Node is installed first (`node -v`), then install dependencies:

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

## Step 9: Verify Installation

Click the site URL in Herd or visit it in your browser (for example, `https://my-project.test`). You should see your Laravel application homepage.

## Common Issues and Solutions

### ".test" Site Not Loading
On Windows, `.test` domains require local DNS resolution which Herd handles via the hosts file or a local DNS service.
- **Antivirus Interference:** Some antivirus software (like Avast or Bitdefender) may block hosts file modifications. You might need to allow Herd.
- **Chrome Secure DNS:** If Chrome forces "Secure DNS", it might bypass local resolution. Disable "Use Secure DNS" in Chrome settings if experiencing issues.

### Permission Errors
Windows file permissions differ from Unix. If you see "Permission denied" errors for `storage` or `bootstrap/cache`:
- Right-click your `my-project` folder.
- Properties > Security.
- Verify your user account has **Full Control**.

### Database Connection Failures
- **Wrong Port:** Check if another service (like XAMPP/MySQL) is already running on port 3306.
- **Credentials:** Re-verify username/password. Try connecting via a tool like TablePlus or HeidiSQL first to confirm credentials are correct.

### PowerShell vs Command Prompt
For most Laravel commands (`php artisan`), both work fine. However, some advanced terminal commands (like `grep` or `sudo`) won't work on Windows. Use Git Bash (included with Git for Windows) if you need a Unix-like terminal experience.

## Quick Setup Checklist

```bash
# 1. Install Git, Composer, & Node.js (if missing)

# 2. Clone or create project
git clone https://github.com/username/project.git
cd project

# 3. Install dependencies
composer install
copy .env.example .env

# 4. Configure environment
php artisan key:generate

# 5. Install Node dependencies
npm install

# 6. Configure .env with Database credentials

# 7. Run migrations
php artisan migrate

# 8. Build assets
npm run dev
```

Add site in Herd interface and visit the assigned URL.

## Best Practices

Keep `APP_ENV=local` and `APP_DEBUG=true` during local development.

Herd's PHP version switching makes it easy to test projects with different PHP requirements. Use this feature to ensure compatibility before deploying.

If you previously used WSL (Windows Subsystem for Linux), try to keep your Herd projects in standard Windows folders (e.g., `C:\Users\Name\Projects`) rather than inside the WSL file system (`\\wsl$\...`) for better performance with Herd.

## Conclusion

Laravel Herd transforms Windows Laravel development from a configuration-intensive process into a streamlined workflow. The unified interface for managing PHP versions and sites reduces setup friction significantly.

Once configured, Herd requires minimal maintenance and provides a consistent development environment that closely mirrors modern production setups.
