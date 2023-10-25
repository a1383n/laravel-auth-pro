<?php

namespace LaravelAuthPro\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use LaravelAuthPro\Http\Requests\TokenRequest;

class AuthController extends Controller
{
    public function token(TokenRequest $request): JsonResponse
    {
        // Not implemented
        throw new \Exception('not implemented');
    }
}
