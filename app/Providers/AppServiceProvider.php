<?php

namespace App\Providers;

use App\Services\Internxt;
use App\Services\IPStack;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Internxt::class);
        $this->app->bind(IPStack::class);
    }

    public function boot(): void
    {
        //
    }
}
