# How to Install and Configure Nginx on Ubuntu (Beginner-Friendly Guide)

Nginx is a high-performance web server widely used for hosting blogs, portfolios, APIs, and large-scale applications. It is known for its speed, low memory usage, and ability to handle high traffic efficiently.

This guide explains how to install and configure Nginx on Ubuntu step by step, making it easy for beginners while following production-ready practices.

## Prerequisites

Before starting, ensure you are using Ubuntu 20.04 or later, have sudo access on the server or VPS, and basic familiarity with Linux commands.

## Step 1: Update Your System

Always update package lists before installing new software to avoid dependency issues.

```bash
sudo apt update && sudo apt upgrade -y
```

## Step 2: Install Nginx

Install Nginx from Ubuntu's official repositories.

```bash
sudo apt install nginx -y
```

Confirm that the service is running.

```bash
systemctl status nginx
```

## Step 3: Allow Nginx Through Firewall

If UFW is enabled, allow HTTP and HTTPS traffic.

```bash
sudo ufw allow 'Nginx Full'
sudo ufw reload
```

## Step 4: Test Nginx

Open your browser and visit your server's IP address. You should see the default Nginx welcome page, confirming the installation.

## Step 5: Create a Server Block

Create a new configuration file for your domain.

```bash
sudo nano /etc/nginx/sites-available/yourdomain
```

Define the server name, document root, and index file. Enable the configuration by linking it to the sites-enabled directory.

```bash
sudo ln -s /etc/nginx/sites-available/yourdomain /etc/nginx/sites-enabled/
```

Test the configuration.

```bash
sudo nginx -t
```

Reload Nginx to apply changes.

```bash
sudo systemctl reload nginx
```

## Step 6: Enable HTTPS

Secure your site with a free SSL certificate using Let's Encrypt.

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx
```

## Common Issues

403 errors usually indicate permission problems, while 502 errors mean the backend service is not responding. Always check logs using `/var/log/nginx/error.log`.

## Conclusion

Nginx is a fast and reliable web server suitable for beginners and production environments. Once configured correctly, it requires minimal maintenance and delivers excellent performance.
