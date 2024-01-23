<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function list() : Response
    {
        // TODO: Implement list() method.
        return HttpResponse::success('Permission List');
    }
}
