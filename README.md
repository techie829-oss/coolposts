# CoolPosts 🚀

CoolPosts is a modern, feature-rich blogging platform built with Laravel. It focuses on empowering creators with AI-assisted writing, robust monetization tools, and a seamless user experience.

## ✨ Key Features

- **📝 AI-Assisted Blogging**: Generate full posts, optimize content, and improve writing style using Gemini AI.
- **☁️ Cloudinary Integration**: Optimized media storage and delivery for fast, responsive images.
- **💰 Built-in Monetization**:
    - **Ad Revenue**: Time-based and impression-based earning models.
    - **Countdown Timers**: Monetized link sharing with customizable countdowns.
- **🎨 Professional Templates**: Pre-built templates for Tutorials, Reviews, News, Case Studies, and more.
- **📧 Newsletter System**: Integrated subscription management to grow your audience.
- **📊 Smart Analytics**: Track post views and engagement (intelligent filtering for admin visits).
- **🔎 SEO Optimized**: Automatic JSON-LD Schema, OpenGraph tags, and sitemaps.

## 🛠 Tech Stack

- **Framework**: Laravel 10.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Services**: Cloudinary (Media), Google Gemini (AI)

## 🚀 Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/techie829-oss/coolposts.git
   cd coolposts
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configuration**
   Update your `.env` file with your credentials:
   - Database connection
   - Cloudinary credentials (`CLOUDINARY_URL`)
   - Google Gemini API Key (`GEMINI_API_KEY`)

5. **Database Migration**
   ```bash
   php artisan migrate
   ```

6. **Build Assets & Serve**
   ```bash
   npm run dev
   # In a separate terminal
   php artisan serve
   ```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
