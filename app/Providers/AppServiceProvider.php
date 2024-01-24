<?php

namespace App\Providers;

use App\Console\Commands\GeneratePermissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('HttpResponse', function() {
            return new \App\Http\Responses\HttpResponse();
        });
    }
}
