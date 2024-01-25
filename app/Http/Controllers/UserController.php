<?php
namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function me(): Response
    {
        $user = Auth::user();

        $permissions = $user->getAllPermissions()->pluck('name');

        $result = $user->with('roles', 'entity')->first()->toArray();

        $result['roles'] = Arr::pluck($result['roles'], 'name');

        $result['permissions'] = $permissions;

        return HttpResponse::success('Success', [
            'user' => $result
        ]);
    }

    public function create(Request $request): Response
    {
        // TODO: Implement create() method.
        return HttpResponse::success('User Created');
    }

    public function disable(Request $request): Response
    {
        // TODO: Implement disable() method.
        return HttpResponse::success('User Disabled');
    }

    public function update(Request $request): Response
    {
        // TODO: Implement update() method.
        return HttpResponse::success('User Updated');
    }

    public function assignRole(Request $request): Response
    {
        // TODO: Implement assignRole() method.
        return HttpResponse::success('Role Assigned');
    }
}
