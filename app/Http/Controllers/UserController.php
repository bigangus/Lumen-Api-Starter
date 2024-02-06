<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function update(Request $request): Response
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'phone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        $user = User::query()->find($request->input('id'));

        if (!$user) {
            return HttpResponse::error('User not found');
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return HttpResponse::success('User Updated', [
            'user' => $user
        ]);
    }

    public function assignRole(Request $request): Response
    {
        // TODO: Implement assignRole() method.
        return HttpResponse::success('Role Assigned');
    }
}
