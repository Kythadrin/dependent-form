<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\CountryDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const int COUNTRY_DATA_CACHE_LIFETIME = 86400; // 24 hours

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
        Cache::remember('countries_data', self::COUNTRY_DATA_CACHE_LIFETIME, function () {
            $countryDataService = new CountryDataService();
            return $countryDataService->loadAndValidateData();
        });
    }
}
