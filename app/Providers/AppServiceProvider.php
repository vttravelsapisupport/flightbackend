<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->isLocal()) {
            // DB::listen(function($query) {
            //     Log::info(
            //         $query->sql,
            //         $query->bindings,
            //         $query->time
            //     );
            // });

                // DB::listen(function ($query) {
                //     if ($this->shouldSkipLogging($query)) {
                //         return;
                //     }
                //     \App\Models\QueryLog::create([
                //         'query'    => json_encode($query->sql),
                //         'bindings' => json_encode($query->bindings),
                //         'time'     => $query->time,
                //     ]);
                // });
        }
        else {
            \URL::forceScheme('https');
        }


        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rs ' . number_format($amount, 2); ?>";
        });
    }
    protected function shouldSkipLogging($query)
    {
        // Add conditions here to skip logging specific queries
        // For example, if the query contains a specific keyword or table name

        $skipKeywords = ['insert into `query_logs`']; // Adjust based on your requirements

        foreach ($skipKeywords as $keyword) {
            if (strpos($query->sql, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

