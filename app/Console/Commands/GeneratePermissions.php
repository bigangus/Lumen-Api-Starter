<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Commands\CreatePermission;
use Spatie\Permission\Commands\CreateRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GeneratePermissions extends Command
{
    protected $signature = 'permissions:generate';

    protected $description = 'Generate all permissions via routes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Artisan::call(CreateRole::class, [
            'name' => 'Super Admin'
        ]);

        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $route) {
            if (isset($route['action']['middleware']) && in_array('acl', $route['action']['middleware'])) {
                Artisan::call(CreatePermission::class, [
                    'name' => $route['uri']
                ]);
            }
        }

        Role::findByName('Super Admin')->syncPermissions(Permission::all());
    }
}
