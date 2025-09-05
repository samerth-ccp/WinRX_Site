<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use DB;

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
        //
        if (app()->environment() !== 'local') {
        URL::forceScheme('https');
        }

        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
        $configData = array_column($configData,'config_value','config_key');
        Session::forget('ConfigData');
        Session::put('ConfigData', $configData);

        Paginator::useBootstrap();
    }
}
