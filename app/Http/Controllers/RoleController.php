<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function create(Request $request): Response
    {
        $this->validate($request, [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        if (!is_subset($request->input('permissions'), Permission::all()->pluck('name')->toArray())) {
            return HttpResponse::error('Invalid permissions', [], 400);
        }

        if (!Auth::user()->hasAllPermissions($request->input('permissions'))) {
            return HttpResponse::error('You do not have permission to do this', [], 401);
        }

        Role::create(['name' => $request->input('name')]);

        $role = Role::findByName($request->input('name'))->syncPermissions($request->input('permissions'));

        return HttpResponse::success('Role Created', [
            'role' => $role
        ]);
    }

    public function update(Request $request): Response
    {
        // TODO: Implement update() method.
        return HttpResponse::success('Role Updated');
    }

    public function delete(Request $request): Response
    {
        // TODO: Implement delete() method.
        return HttpResponse::success('Role Deleted');
    }
}
