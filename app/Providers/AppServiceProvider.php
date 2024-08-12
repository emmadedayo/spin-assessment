<?php

namespace App\Providers;

use App\Http\Interfaces\RepositoryInterface;
use App\Http\Repositories\TaskRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(RepositoryInterface::class, TaskRepository::class);
        $this->app->bind(RepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
