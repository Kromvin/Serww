<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function toResource(): \App\Http\Resources\LoginResource
    {
        return new \App\Http\Resources\LoginResource($this->validated());
    }
}
