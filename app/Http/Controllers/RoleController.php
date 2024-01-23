<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function create(Request $request): Response
    {
        // TODO: Implement create() method.
        return HttpResponse::success('Role Created');
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
