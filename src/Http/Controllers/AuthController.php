<?php

namespace LaravelAuthPro\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Http\Requests\TokenRequest;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
        //
    }

    public function token(TokenRequest $request)
    {
        $result = $this->authService->loginWithCredential($request->getAuthCredential());

        if ($result->isSuccessful()) {
                Auth::login($result->getUser());

            return response([
                'token' => Auth::user()->createToken()
            ])
        }
    }
}
