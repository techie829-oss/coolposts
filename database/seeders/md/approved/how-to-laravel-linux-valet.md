# How to Set Up Laravel Development Environment on Linux (Ubuntu/Debian)

While macOS and Windows developers enjoy the simplicity of **Laravel Herd**, there is currently **no official Herd version for Linux**.

However, Linux developers can achieve a nearly identical "native" experience using **Valet Linux**. Just like Herd, it serves sites locally without Docker complexity, providing lightning-fast performance on Ubuntu, Debian, and Fedora.

This guide covers how to set up this Herd-like environment using Valet Linux.

## Why Use Valet Linux?

Valet Linux is a port of Laravel Valet for Ubuntu/Debian/Fedora. It automatically configures Nginx to serve your sites with a lightweight DNSmasq setup.

- **Fast & Native**: Runs directly on your hardware, no virtualization overhead.
- **Automatic SSL**: Secure your local sites (`.test`) with a single command.
- **Easy Sharing**: Share local sites publicly with `valet share`.

## Prerequisites

- Ubuntu 22.04 LTS or 24.04 LTS (or Debian equivalent)
- `sudo` access
- Basic terminal familiarity

## Step 1: Install Dependencies

Update your system and install system requirements:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y ca-certificates apt-transport-https software-properties-common
```

## Step 2: Install PHP

Add the Ondřej Surý PPA for the latest PHP versions:

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

Install PHP 8.3 (or your preferred version) and extensions:

```bash
sudo apt install -y php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-mbstring php8.3-mysql php8.3-xml php8.3-zip php8.3-sqlite3 php8.3-intl php8.3-bcmath php8.3-gd
```

## Step 3: Install Composer

Install Composer globally:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Step 4: Install Valet Linux

Install Valet using Composer:

```bash
composer global require cpriego/valet-linux
```

Make sure your system assumes global composer binaries are in your path. Add this to your `~/.bashrc` or `~/.zshrc`:

```bash
export PATH=$PATH:$HOME/.config/composer/vendor/bin
```

Reload your shell:

```bash
source ~/.bashrc
```

Run the Valet install command:

```bash
valet install
```

This configures Nginx and Dnsmasq automatically.

## Step 5: Install Database (MySQL/MariaDB)

Install MariaDB (drop-in replacement for MySQL):

```bash
sudo apt install -y mariadb-server
sudo mysql_secure_installation
```

Start the service:

```bash
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

Create a database user for development:

```bash
sudo mysql -u root
```

```sql
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'laravel'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
```

## Step 6: Serve Your Projects

Create a directory for your projects:

```bash
mkdir ~/Projects
cd ~/Projects
```

Tell Valet to serve this directory:

```bash
valet park
```

Now, any folder inside `~/Projects` will be accessible at `http://folder-name.test`.

## Step 7: Create a Laravel Project

```bash
composer create-project laravel/laravel cool-app
```

Visit `http://cool-app.test` in your browser. It should load instantly!

## Step 8: Enable HTTPS (Optional)

To secure your site with a local trusted certificate:

```bash
cd cool-app
valet secure
```

Your site is now at `https://cool-app.test`.

## Conclusion

With Valet Linux, you get the convenience of "park and play" just like macOS developers. It's significantly faster than Docker for file-heavy operations and uses fewer system resources. For Linux users who prefer a native development experience, this is the ultimate setup.
