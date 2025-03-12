<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        /**
         * Directive to include line breaks in a translation / string
         */
        Blade::directive('local2br', function ($expression) {
            return "<?php echo nl2br(e(app('translator')->get({$expression}))); ?>";
        });
    }
}
