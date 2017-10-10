<?php

namespace App\Providers;

use App\Repository\Contracts\CacheableContract;
use App\Repository\SimpleArrayCacheRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * It resolves some insufficient index key length in MariaDB < 10.2 and MySQL < 5.7.7
         */
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CacheableContract::class, SimpleArrayCacheRepository::class);
    }
}
