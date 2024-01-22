<?php

namespace App\Http\Responses\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Http\Responses\HttpResponse success(string $message, array $data = []);
 * @method static \App\Http\Responses\HttpResponse error(string $message, array $data = [], int $code = 500);
 */
class HttpResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'HttpResponse';
    }
}
