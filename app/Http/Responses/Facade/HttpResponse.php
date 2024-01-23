<?php

namespace App\Http\Responses\Facade;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Response success(string $message, array $data = []);
 * @method static Response error(string $message, array $data = [], int $code = 500);
 */
class HttpResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'HttpResponse';
    }
}
