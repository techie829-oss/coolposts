<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DownloadGeoIPDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geoip:download {--force : Force download even if file exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download GeoIP database from MaxMind';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting GeoIP database download...');

        $licenseKey = config('services.maxmind.license_key');
        $databasePath = storage_path('app/geoip/GeoLite2-Country.mmdb');
        $databaseDir = dirname($databasePath);

        // Check if license key is configured
        if (!$licenseKey) {
            $this->error('MaxMind license key not configured. Please set MAXMIND_LICENSE_KEY in your .env file.');
            return 1;
        }

        // Create directory if it doesn't exist
        if (!file_exists($databaseDir)) {
            mkdir($databaseDir, 0755, true);
            $this->info("Created directory: {$databaseDir}");
        }

        // Check if database already exists
        if (file_exists($databasePath) && !$this->option('force')) {
            $this->warn('GeoIP database already exists. Use --force to re-download.');
            return 0;
        }

        try {
            $this->info('Downloading GeoLite2 Country database...');

            // MaxMind GeoLite2 download URL
            $url = "https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key={$licenseKey}&suffix=tar.gz";

            $response = Http::timeout(300)->get($url);

            if (!$response->successful()) {
                $this->error('Failed to download database. HTTP Status: ' . $response->status());
                return 1;
            }

            // Save the downloaded file
            $tempFile = tempnam(sys_get_temp_dir(), 'geoip_');
            file_put_contents($tempFile, $response->body());

            // Extract the tar.gz file
            $this->info('Extracting database...');
            $extractDir = sys_get_temp_dir() . '/geoip_extract_' . uniqid();
            mkdir($extractDir);

            // Extract tar.gz
            $phar = new \PharData($tempFile);
            $phar->extractTo($extractDir);

            // Find the .mmdb file
            $mmdbFile = $this->findMMDBFile($extractDir);
            if (!$mmdbFile) {
                $this->error('Could not find .mmdb file in downloaded archive');
                return 1;
            }

            // Move to final location
            if (file_exists($databasePath)) {
                unlink($databasePath);
            }
            rename($mmdbFile, $databasePath);

            // Clean up
            unlink($tempFile);
            $this->removeDirectory($extractDir);

            $this->info('GeoIP database downloaded successfully!');
            $this->info("Database location: {$databasePath}");
            $this->info('File size: ' . $this->formatBytes(filesize($databasePath)));

            // Test the database
            $this->testDatabase($databasePath);

            return 0;

        } catch (\Exception $e) {
            $this->error('Error downloading GeoIP database: ' . $e->getMessage());
            Log::error('GeoIP database download failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Find the .mmdb file in the extracted directory
     */
    protected function findMMDBFile(string $dir): ?string
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'mmdb') {
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Remove directory recursively
     */
    protected function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($dir);
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Test the downloaded database
     */
    protected function testDatabase(string $databasePath): void
    {
        try {
            $reader = new \GeoIp2\Database\Reader($databasePath);

            // Test with Google DNS IP
            $record = $reader->country('8.8.8.8');

            $this->info('Database test successful!');
            $this->info("Test IP (8.8.8.8) -> Country: {$record->country->name} ({$record->country->isoCode})");

            $reader->close();
        } catch (\Exception $e) {
            $this->warn('Database test failed: ' . $e->getMessage());
        }
    }
}
