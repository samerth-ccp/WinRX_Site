<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Session;
use DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray(); 
        $configData = array_column($configData,'config_value','config_key');
        Session::forget('ConfigData');
        Session::put('ConfigData', $configData);
    }
}
