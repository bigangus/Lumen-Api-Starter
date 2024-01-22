<?php

namespace App\Http\Responses;
use JsonSerializable;

class HttpResponse implements JsonSerializable
{
    public int $code;
    public bool $status;
    public string $message;
    public array $data;

    public function __construct(bool $status = true, int $code = 200, string $message = 'success', array $data = [])
    {
        $this->code = $code;
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function success(string $message, array $data = []): static
    {
        $this->message = $message;
        $this->data = $data;
        return $this;
    }

    public function error(string $message, array $data = [], int $code = 500): static
    {
        $this->status = false;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'status' => $this->status,
            'message' => __($this->message),
            'data' => $this->data
        ];
    }
}
