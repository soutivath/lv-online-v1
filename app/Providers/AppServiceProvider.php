<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Create fonts directory if it doesn't exist
        $fontsDir = storage_path('fonts');
        if (!File::isDirectory($fontsDir)) {
            File::makeDirectory($fontsDir, 0755, true);
        }

        // Check if we need to copy the Phetsarath font files to storage
        $phetsarathFont = $fontsDir . '/Phetsarath_OT.ttf';
        if (!File::exists($phetsarathFont)) {
            // Copy Phetsarath font files from resources to storage
            $sourceDir = resource_path('fonts/phetsarath');
            if (File::isDirectory($sourceDir)) {
                $files = File::files($sourceDir);
                foreach ($files as $file) {
                    File::copy($file, $fontsDir . '/' . $file->getFilename());
                }
            }
        }
    }
}
