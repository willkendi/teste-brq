<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Se usa interface, vincula a interface à implementação
        $this->app->bind(
            TransactionRepository::class
        );

        // Bind do Service, se necessário (geralmente não precisa)
        $this->app->bind(TransactionService::class, function ($app) {
            return new TransactionService($app->make(TransactionRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
