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
        $settings = GlobalSetting::first();
        $platformName = $settings->platform_name ?? 'CoolPosts Posts';

        return response()->view('legal.sitemap', compact('platformName'))
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
