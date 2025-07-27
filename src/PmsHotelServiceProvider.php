<?php

namespace Kaely\PmsHotel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Console\Commands\InstallPmsHotelCommand;
use Kaely\PmsHotel\Console\Commands\SeedPmsHotelCommand;
use Kaely\PmsHotel\Console\Commands\GeneratePmsModulesCommand;
use Kaely\PmsHotel\Models\RoomRateRule;
use Kaely\PmsHotel\Models\Food;
use Kaely\PmsHotel\Models\FoodType;
use Kaely\PmsHotel\Models\Dish;
use Kaely\PmsHotel\Models\Restaurant;
use Kaely\PmsHotel\Models\RestaurantReservation;
use Kaely\PmsHotel\Policies\RoomRateRulePolicy;
use Kaely\PmsHotel\Policies\FoodPolicy;
use Kaely\PmsHotel\Policies\FoodTypePolicy;
use Kaely\PmsHotel\Policies\DishPolicy;
use Kaely\PmsHotel\Policies\RestaurantPolicy;
use Kaely\PmsHotel\Policies\RestaurantReservationPolicy;

class PmsHotelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/pms-hotel.php', 'pms-hotel'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/pms-hotel.php' => config_path('pms-hotel.php'),
        ], 'pms-hotel-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations/tenant'),
        ], 'pms-hotel-migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'pms-hotel-migrations-main');

        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'pms-hotel-seeders');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPmsHotelCommand::class,
                SeedPmsHotelCommand::class,
                GeneratePmsModulesCommand::class,
            ]);
        }

        $this->registerRoutes();
        $this->registerPolicies();
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => 'api/pms',
            'middleware' => ['api', 'auth:sanctum'],
            'namespace' => 'Kaely\\PmsHotel\\Http\\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register the package policies.
     */
    protected function registerPolicies(): void
    {
        // Register policies
        Gate::policy(RoomRateRule::class, RoomRateRulePolicy::class);
        Gate::policy(Food::class, FoodPolicy::class);
        Gate::policy(FoodType::class, FoodTypePolicy::class);
        Gate::policy(Dish::class, DishPolicy::class);
        Gate::policy(Restaurant::class, RestaurantPolicy::class);
        Gate::policy(RestaurantReservation::class, RestaurantReservationPolicy::class);
    }
}