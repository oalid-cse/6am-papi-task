<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiResponseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\Response;

class LoginController extends ApiResponseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->accessToken;
            return $this->successResponse(
                ['token' => $token],
                'User authenticated successfully'
            );
        }

        return $this->validationError('Invalid credentials', Response::HTTP_UNAUTHORIZED);
    }
}
