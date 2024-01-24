<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function list() : Response
    {
        $permissions = Auth::user()->getAllPermissions()->map(function($item) {
            return [
                'name' => $item['name'],
                'translation' => __($item['name'])
            ];
        })->whereNotIn('name', config('permission.basic'))->values();

        return HttpResponse::success('Get permissions success', [
            'permissions' => $permissions->toArray()
        ]);
    }
}
