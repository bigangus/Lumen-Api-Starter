<?php
namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me(): Response
    {
        return HttpResponse::success('Success', [
            'user' => Auth::user()
        ]);
    }
}
