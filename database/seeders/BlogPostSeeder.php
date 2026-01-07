<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $blogPosts = [
            [
                'title' => 'How to Install and Configure Nginx on Ubuntu | Step-by-Step Guide',
                'excerpt' => 'Learn how to install, configure, and optimize Nginx web server on Ubuntu with this comprehensive step-by-step guide.',
                'content' => "Nginx is a powerful, high-performance web server that's become the preferred choice for many developers and system administrators. In this comprehensive guide, we'll walk through the complete process of installing and configuring Nginx on Ubuntu, from initial setup to advanced configuration options.\n\n## Prerequisites\n\nBefore we begin, ensure you have:\n\n- Ubuntu 20.04 LTS or later\n- Sudo privileges on your system\n- Basic knowledge of Linux command line\n- A domain name (optional but recommended)\n\n## Step 1: Update System Packages\n\nFirst, let's ensure your system is up to date:\n\n```bash\nsudo apt update\nsudo apt upgrade -y\n```\n\nThis ensures you have the latest security patches and package versions.\n\n## Step 2: Install Nginx\n\nInstall Nginx using the package manager:\n\n```bash\nsudo apt install nginx -y\n```\n\nAfter installation, verify that Nginx is running:\n\n```bash\nsudo systemctl status nginx\n```\n\nYou should see output indicating that Nginx is active and running.\n\n## Step 3: Configure Firewall\n\nConfigure UFW (Uncomplicated Firewall) to allow HTTP and HTTPS traffic:\n\n```bash\nsudo ufw allow 'Nginx Full'\nsudo ufw enable\n```\n\nCheck the firewall status:\n\n```bash\nsudo ufw status\n```\n\n## Step 4: Basic Nginx Configuration\n\nNginx's main configuration file is located at `/etc/nginx/nginx.conf`. Let's examine the default configuration:\n\n```bash\nsudo nano /etc/nginx/nginx.conf\n```\n\nThe default configuration includes:\n\n- Worker processes configuration\n- Event handling settings\n- HTTP block with global settings\n- Server blocks for virtual hosts\n\n## Step 5: Create Server Block (Virtual Host)\n\nCreate a new server block for your website:\n\n```bash\nsudo nano /etc/nginx/sites-available/yourdomain.com\n```\n\nAdd the following configuration:\n\n```nginx\nserver {\n    listen 80;\n    listen [::]:80;\n    server_name yourdomain.com www.yourdomain.com;\n    root /var/www/yourdomain.com/html;\n    index index.html index.htm index.nginx-debian.html;\n\n    location / {\n        try_files uri uri/ =404;\n    }\n\n    location ~ \\.php$ {\n        include snippets/fastcgi-php.conf;\n        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;\n    }\n\n    location ~ /\\.ht {\n        deny all;\n    }\n}\n```\n\n## Step 6: Enable the Site\n\nCreate a symbolic link to enable the site:\n\n```bash\nsudo ln -s /etc/nginx/sites-available/yourdomain.com /etc/nginx/sites-enabled/\n```\n\nRemove the default site (optional):\n\n```bash\nsudo rm /etc/nginx/sites-enabled/default\n```\n\n## Step 7: Test Configuration\n\nBefore restarting Nginx, test the configuration:\n\n```bash\nsudo nginx -t\n```\n\nIf the test passes, restart Nginx:\n\n```bash\nsudo systemctl restart nginx\n```\n\n## Step 8: Create Website Directory\n\nCreate the directory for your website:\n\n```bash\nsudo mkdir -p /var/www/yourdomain.com/html\n```\n\nSet proper permissions:\n\n```bash\nsudo chown -R user:user /var/www/yourdomain.com/html\nsudo chmod -R 755 /var/www/yourdomain.com\n```\n\n## Step 9: Create Sample HTML File\n\nCreate a simple HTML file to test your setup:\n\n```bash\nnano /var/www/yourdomain.com/html/index.html\n```\n\nAdd this content:\n\n```html\n<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    <title>Welcome to Your Domain</title>\n</head>\n<body>\n    <h1>Success! Nginx is working!</h1>\n    <p>If you can see this page, Nginx has been successfully installed and configured.</p>\n</body>\n</html>\n```\n\n## Step 10: SSL/HTTPS Configuration (Optional but Recommended)\n\nInstall Certbot for Let's Encrypt SSL certificates:\n\n```bash\nsudo apt install certbot python3-certbot-nginx -y\n```\n\nObtain an SSL certificate:\n\n```bash\nsudo certbot --nginx -d yourdomain.com -d www.yourdomain.com\n```\n\nFollow the prompts to complete the SSL setup.\n\n## Advanced Configuration Options\n\n### Performance Optimization\n\nAdd these settings to your server block for better performance:\n\n```nginx\n# Enable gzip compression\nlocation ~* \\.(css|js|png|jpg|jpeg|gif|ico|svg)$ {\n    expires 1y;\n    add_header Cache-Control \"public, immutable\";\n}\n\n# Security headers\nadd_header X-Frame-Options \"SAMEORIGIN\" always;\nadd_header X-XSS-Protection \"1; mode=block\" always;\nadd_header X-Content-Type-Options \"nosniff\" always;\nadd_header Referrer-Policy \"no-referrer-when-downgrade\" always;\nadd_header Content-Security-Policy \"default-src 'self' http: https: data: blob: 'unsafe-inline'\" always;\n```\n\n### Load Balancing\n\nFor load balancing multiple backend servers:\n\n```nginx\nupstream backend {\n    server 127.0.0.1:8001;\n    server 127.0.0.1:8002;\n    server 127.0.0.1:8003;\n}\n\nserver {\n    location / {\n        proxy_pass http://backend;\n        proxy_set_header Host host;\n        proxy_set_header X-Real-IP remote_addr;\n    }\n}\n```\n\n## Troubleshooting Common Issues\n\n### Check Nginx Error Logs\n\n```bash\nsudo tail -f /var/log/nginx/error.log\n```\n\n### Check Nginx Access Logs\n\n```bash\nsudo tail -f /var/log/nginx/access.log\n```\n\n### Common Error Solutions\n\n1. **502 Bad Gateway**: Check if your backend service is running\n2. **403 Forbidden**: Verify file permissions and ownership\n3. **404 Not Found**: Check your root directory path and file existence\n\n## Monitoring and Maintenance\n\n### Check Nginx Status\n\n```bash\nsudo systemctl status nginx\n```\n\n### View Active Connections\n\n```bash\nsudo netstat -tulpn | grep :80\n```\n\n### Monitor Performance\n\n```bash\nsudo nginx -V 2>&1 | grep -o with-http_stub_status_module\n```\n\n## Conclusion\n\nCongratulations! You've successfully installed and configured Nginx on Ubuntu. Your web server is now ready to serve content efficiently and securely.\n\nRemember to:\n- Regularly update Nginx and your system\n- Monitor logs for any issues\n- Keep your SSL certificates up to date\n- Test your configuration after any changes\n\nFor production environments, consider implementing additional security measures and monitoring solutions to ensure optimal performance and reliability.",
                'type' => 'guide',
                'category' => 'Web Development',
                'tags' => ['nginx', 'ubuntu', 'web-server', 'configuration', 'tutorial'],
                'status' => 'published',
                'published_at' => now()->subDay(),
                'is_monetized' => true,
                'monetization_type' => 'time_based',
                'earning_rate_less_2min' => 0.0020,
                'earning_rate_2_5min' => 0.0040,
                'earning_rate_more_5min' => 0.0100,
                'ad_type' => 'banner_ads',
                'views' => 189,
                'unique_visitors' => 145,
            ],
            [
                'title' => 'Getting Started with Laravel: A Complete Guide',
                'excerpt' => 'Learn the basics of Laravel framework and build your first web application with this comprehensive tutorial.',
                'content' => "# Getting Started with Laravel\n\nLaravel is a powerful PHP framework that makes web development a breeze. In this guide, we'll walk through the essential concepts and help you build your first Laravel application.\n\n## What is Laravel?\n\nLaravel is a free, open-source PHP web framework created by Taylor Otwell. It follows the MVC (Model-View-Controller) pattern and provides an elegant syntax for common web development tasks.\n\n## Key Features\n\n- **Eloquent ORM**: A beautiful, simple ActiveRecord implementation for working with your database\n- **Blade Templating**: A powerful templating engine\n- **Artisan CLI**: Command-line interface for common tasks\n- **Built-in Security**: CSRF protection, SQL injection prevention, and more\n\n## Installation\n\nTo get started with Laravel, you'll need:\n\n1. PHP 8.1 or higher\n2. Composer\n3. A web server (Apache/Nginx)\n\n## Next Steps\n\nOnce you have Laravel installed, you can start building your application. Check out the official documentation for more advanced topics.",
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel', 'php', 'web-development', 'tutorial'],
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'is_monetized' => true,
                'monetization_type' => 'time_based',
                'earning_rate_less_2min' => 0.0010,
                'earning_rate_2_5min' => 0.0020,
                'earning_rate_more_5min' => 0.0050,
                'ad_type' => 'banner_ads',
                'views' => 1250,
                'unique_visitors' => 890,
            ],
        ];

        foreach ($blogPosts as $postData) {
            BlogPost::create(array_merge($postData, [
                'user_id' => $user->id,
                'slug' => Str::slug($postData['title']),
                'meta_title' => $postData['title'],
                'meta_description' => $postData['excerpt'],
            ]));
        }

        $this->command->info('Sample blog posts created successfully!');
    }
}
