<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    public function list(): Response
    {
        $permissions = Auth::user()->getAllPermissions();

        return HttpResponse::success('Get permissions success', [
            'permissions' => format_permissions($permissions)->toArray()
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function add(Request $request): Response
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'permissions' => 'required|array'
        ]);

        $user = User::query()->find($request->input('user_id'));

        if (!$user) {
            return HttpResponse::error('User not found', [], 404);
        }

        if (Auth::user()->hasAllPermissions($request->input('permissions')) &&
            Auth::user()->canEditThatUser($user->getAttribute('id'))) {
            $user->givePermissionTo($request->input('permissions'));
            return HttpResponse::success('Add permissions success',
                [
                    'permissions' => format_permissions($user->getAllPermissions())->toArray()
                ]
            );
        }

        return HttpResponse::error('You do not have permission to do this', [], 401);
    }

    /**
     * @throws ValidationException
     */
    public function remove(Request $request): Response
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'permissions' => 'required|array'
        ]);

        $user = User::query()->find($request->input('user_id'));

        if (!$user) {
            return HttpResponse::error('User not found', [], 404);
        }

        if (Auth::user()->hasAllPermissions($request->input('permissions')) &&
            Auth::user()->canEditThatUser($user->getAttribute('id'))) {
            $user->revokePermissionTo($request->input('permissions'));
            return HttpResponse::success('Remove permissions success',
                [
                    'permissions' => format_permissions($user->getAllPermissions())->toArray()
                ]
            );
        }

        return HttpResponse::error('You do not have permission to do this', [], 401);
    }

    /**
     * @throws ValidationException
     */
    public function get(Request $request): Response
    {
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);

        $user = User::query()->find($request->input('user_id'));

        if (!$user) {
            return HttpResponse::error('User not found', [], 404);
        }

        if (Auth::user()->canEditThatUser($user->id)) {
            $permissions = $user->getAllPermissions();

            return HttpResponse::success('Get permissions success', [
                'permissions' => format_permissions($permissions)->toArray()
            ]);
        }

        return HttpResponse::error('You do not have permission to do this', [], 401);
    }
}
