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
            'name' => 'Basic'
        ]);

        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $route) {
            Artisan::call(CreatePermission::class, [
                'name' => $route['uri']
            ]);
        }

        $basicPermissions = config('permission.basic');

        foreach ($basicPermissions as $permission) {
            if (Permission::findByName($permission)) {
                Role::findByName('Basic')->givePermissionTo($permission);
            }
        }
    }
}
