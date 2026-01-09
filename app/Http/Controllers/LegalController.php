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
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.terms-of-service', compact('platformName'));
    }

    /**
     * Display Privacy Policy page
     */
    public function privacyPolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.privacy-policy', compact('platformName'));
    }

    /**
     * Display About page
     */
    public function about()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.about', compact('platformName'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.faq', compact('platformName'));
    }

    /**
     * Display Help Documentation page
     */
    public function help()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.help', compact('platformName'));
    }

    /**
     * Display Contact page
     */
    public function contact()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.contact', compact('platformName'));
    }

    /**
     * Display Cookie Policy page
     */
    public function cookiePolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.cookie-policy', compact('platformName'));
    }

    /**
     * Display Refund Policy page
     */
    public function refundPolicy()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.refund-policy', compact('platformName'));
    }

    /**
     * Display Acceptable Use Policy page
     */
    public function acceptableUse()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.acceptable-use', compact('platformName'));
    }

    /**
     * Display DMCA Policy page
     */
    public function dmca()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.dmca', compact('platformName'));
    }

    /**
     * Display GDPR Compliance page
     */
    public function gdpr()
    {
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return view('legal.gdpr', compact('platformName'));
    }

    /**
     * Display Sitemap
     */
    public function sitemap()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // Blog Index
        $xml .= '<url>';
        $xml .= '<loc>' . route('blog.index') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= '</url>';

        // Blog Posts
        $posts = \App\Models\BlogPost::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('blog.show', $post->slug) . '</loc>';
            $xml .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Legal Pages
        $legalPages = [
            ['route' => 'legal.terms', 'priority' => '0.3'],
            ['route' => 'legal.privacy', 'priority' => '0.3'],
            ['route' => 'legal.cookie-policy', 'priority' => '0.3'],
            ['route' => 'legal.disclaimer', 'priority' => '0.3'],
        ];

        foreach ($legalPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . route($page['route']) . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        // Other Pages
        $otherPages = [
            ['route' => 'how-we-work', 'priority' => '0.7'],
            ['route' => 'legal.faq', 'priority' => '0.6'],
            ['route' => 'legal.about-us', 'priority' => '0.5'],
            ['route' => 'legal.contact-us', 'priority' => '0.5'],
        ];

        foreach ($otherPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . route($page['route']) . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
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
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return response()->view('legal.robots', compact('platformName'))
            ->header('Content-Type', 'text/plain');
    }
}
