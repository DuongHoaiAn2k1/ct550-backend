<?php

namespace App\Providers;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;

class CustomMigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom([
            database_path('migrations'),
            database_path('migrations/user'),
            database_path('migrations/category'),
            database_path('migrations/product'),
            database_path('migrations/cart'),
            database_path('migrations/review'),
            database_path('migrations/favorite'),
            database_path('migrations/order'),
            database_path('migrations/notification'),
        ]);
    }
}
