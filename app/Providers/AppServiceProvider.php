<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;



class AppServiceProvider extends ServiceProvider
{

    public $bidings = [
        'App\Services\Interfaces\UserServiceInterfaces' => 'App\Services\UserService',
        'App\Repositories\Interfaces\UserRepositoryInterfaces' => 'App\Repositories\UserRepository',

        'App\Services\Interfaces\UserCatalogueServiceInterfaces' => 'App\Services\UserCatalogueService',
        'App\Repositories\Interfaces\UserCatalogueRepositoryInterfaces' => 'App\Repositories\UserCatalogueRepository',

        'App\Repositories\Interfaces\ProvinceRepositoryInterfaces' => 'App\Repositories\ProvinceRepository',
        'App\Repositories\Interfaces\DistrictRepositoryInterfaces' => 'App\Repositories\DistrictRepository',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach($this->$bidings as $key => $val){
            $this->app->bind($key, $val);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
