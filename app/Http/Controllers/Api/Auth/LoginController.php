<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiResponseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Jobs\LoginLogJob;
use Illuminate\Http\Response;

class LoginController extends ApiResponseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->accessToken;

            dispatch(new LoginLogJob(auth()->user()));
            return $this->successResponse(
                ['token' => $token],
                'User authenticated successfully'
            );
        }

        return $this->validationError('Invalid credentials', Response::HTTP_UNAUTHORIZED);
    }
}
