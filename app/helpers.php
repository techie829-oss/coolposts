<?php

if (!function_exists('cloudinary')) {
    /**
     * Override the broken cloudinary-labs package helper
     * Use Cloudinary SDK directly to bypass the broken service provider
     *
     * @return \Cloudinary\Cloudinary
     */
    function cloudinary()
    {
        return new \Cloudinary\Cloudinary(config('cloudinary.cloud_url'));
    }
}
