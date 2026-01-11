<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * optimize
     *
     * Optimize an uploaded image: resize, convert to WebP, and compress.
     * Returns the path to the optimized temporary file.
     *
     * @param  UploadedFile $file
     * @param  int          $maxWidth
     * @param  int          $quality
     * @return string
     */
    public function optimize(UploadedFile $file, int $maxWidth = 1920, int $quality = 80): string
    {
        // Generate a temporary path for the optimized image
        $tempPath = sys_get_temp_dir() . '/' . Str::random(40) . '.webp';

        try {
            // Create image resource from source
            $sourceImage = $this->createImageResource($file->getRealPath());
            if (!$sourceImage) {
                throw new \Exception("Unsupported image type or corrupted file.");
            }

            // Get original dimensions
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Calculate new dimensions if resizing is needed
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = intval($height * ($maxWidth / $width));
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            // Create new true color image
            $optimizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG/WebP
            imagealphablending($optimizedImage, false);
            imagesavealpha($optimizedImage, true);
            $transparent = imagecolorallocatealpha($optimizedImage, 255, 255, 255, 127);
            imagefilledrectangle($optimizedImage, 0, 0, $newWidth, $newHeight, $transparent);

            // Resample
            imagecopyresampled(
                $optimizedImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );

            // Save as WebP
            imagewebp($optimizedImage, $tempPath, $quality);

            // Free memory
            imagedestroy($sourceImage);
            imagedestroy($optimizedImage);

            return $tempPath;
        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            // Fallback: return original path if optimization fails
            return $file->getRealPath();
        }
    }

    /**
     * Create image resource from file based on mime type
     *
     * @param string $path
     * @return \GdImage|resource|false
     */
    protected function createImageResource(string $path)
    {
        $info = getimagesize($path);
        $mime = $info['mime'] ?? null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                return false;
        }
    }
}
