<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalSetting;

class LegalController extends Controller
{
    /**
     * Display Terms of Service page
     */
    public function termsOfService()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.terms-of-service', compact('platformName'));
    }

    /**
     * Display Privacy Policy page
     */
    public function privacyPolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.privacy-policy', compact('platformName'));
    }

    /**
     * Display About page
     */
    public function about()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.about', compact('platformName'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.faq', compact('platformName'));
    }

    /**
     * Display Help Documentation page
     */
    public function help()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.help', compact('platformName'));
    }

    /**
     * Display Contact page
     */
    public function contact()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.contact', compact('platformName'));
    }

    /**
     * Display Cookie Policy page
     */
    public function cookiePolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.cookie-policy', compact('platformName'));
    }

    /**
     * Display Refund Policy page
     */
    public function refundPolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.refund-policy', compact('platformName'));
    }

    /**
     * Display Acceptable Use Policy page
     */
    public function acceptableUse()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.acceptable-use', compact('platformName'));
    }

    /**
     * Display DMCA Policy page
     */
    public function dmca()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.dmca', compact('platformName'));
    }

    /**
     * Display GDPR Compliance page
     */
    public function gdpr()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return view('legal.gdpr', compact('platformName'));
    }

    /**
     * Display Sitemap
     */
    public function sitemap()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . url('/') . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>daily</changefreq>' . "\n";
        $xml .= '    <priority>1.0</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // Blog Index
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('blog.index') . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>daily</changefreq>' . "\n";
        $xml .= '    <priority>0.9</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // Blog Templates
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('blog.templates') . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>monthly</changefreq>' . "\n";
        $xml .= '    <priority>0.8</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // Blog Posts
        $posts = \App\Models\BlogPost::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('blog.show', $post->slug) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $post->updated_at->toAtomString() . '</lastmod>' . "\n";
            $xml .= '    <changefreq>weekly</changefreq>' . "\n";
            $xml .= '    <priority>0.7</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        // Legal Pages
        $legalPages = [
            ['route' => 'legal.terms', 'priority' => '0.3'],
            ['route' => 'legal.privacy', 'priority' => '0.3'],
            ['route' => 'legal.cookies', 'priority' => '0.3'],
            ['route' => 'legal.about', 'priority' => '0.4'],
            ['route' => 'legal.faq', 'priority' => '0.5'],
            ['route' => 'legal.help', 'priority' => '0.4'],
            ['route' => 'legal.contact', 'priority' => '0.4'],
        ];

        foreach ($legalPages as $page) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route($page['route']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        // Other Pages
        $otherPages = [
            ['route' => 'blog.how-we-work', 'priority' => '0.6'],
        ];

        foreach ($otherPages as $page) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route($page['route']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display Robots.txt
     */
    public function robots()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts';

        return response()->view('legal.robots', compact('platformName'))
            ->header('Content-Type', 'text/plain');
    }
}
