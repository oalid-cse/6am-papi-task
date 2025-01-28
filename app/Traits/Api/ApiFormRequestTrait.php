<?php

namespace App\Traits\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as HttpResponse;

trait ApiFormRequestTrait
{
    public function failedValidation(Validator $validator) {
        $errorMessages = implode("\n", $validator->errors()->all());
        $response = [
            'message' => 'VALIDATION_ERROR',
            'description' => $errorMessages,
            'errors' => [
                'detail' => $validator->errors()
            ]
        ];
        throw new HttpResponseException(response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
