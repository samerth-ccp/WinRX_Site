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
        /*if (app()->environment() !== 'local') {
        URL::forceScheme('https');
        }*/
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        // Handle preflight OPTIONS requests gracefully
        if (request()->getMethod() === 'OPTIONS') {
            abort(204); // No content
        }

        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
        $configData = array_column($configData,'config_value','config_key');
        Session::forget('ConfigData');
        Session::put('ConfigData', $configData);

        Paginator::useBootstrap();
    }
}
