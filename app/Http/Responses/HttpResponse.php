<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use JsonSerializable;

class HttpResponse implements JsonSerializable
{
    public int $code;
    public bool $status;
    public string $message;
    public array $data;
    public string $ticket;

    public function __construct(bool $status = true, int $code = 200, string $message = 'success', array $data = [])
    {
        $this->code = $code;
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->ticket = hexdec(uniqid());
    }

    public function success(string $message, array $data = []): Response
    {
        $this->message = $message;
        $this->data = $data;
        return response($this, 200);
    }

    public function error(string $message, array $data = [], int $code = 500): Response
    {
        $this->status = false;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
        return response($this, $code);
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'status' => $this->status,
            'message' => __($this->message),
            'data' => $this->data,
            'ticket' => $this->ticket
        ];
    }
}
