<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use function PHPUnit\Framework\isString;

class ApiResponseController extends Controller
{
    protected $service;
    public function __construct($service=null)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function successResponse(array|object $data=[], $description='Success'): JsonResponse
    {
        $response = [
            'message' => 'OK',
            'description' => $description,
            'data' => $data
        ];
        return response()->json($response, HttpResponse::HTTP_OK);
    }

    /**
     * @param MessageBag|array $errorBag
     * @return JsonResponse
     */
    protected function validationError(MessageBag|array|string $errorBag): JsonResponse
    {
        //make errorBag as a string with separator as line break

        if (is_array($errorBag)) {
            $errorMessages = $this->convertMultiDimensionalArrayToString($errorBag);
        } elseif (isString($errorBag)) {
            $errorMessages = $errorBag;
            $errorBag = [$errorBag];
        } else {
            $errorMessages = implode("\n", $errorBag->all());
        }
        $response = [
            'message' => 'VALIDATION_ERROR',
            'description' => $errorMessages,
            'errors' => [
                'detail' => $errorBag
            ]
        ];
        return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function convertMultiDimensionalArrayToString($array): string
    {
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            return implode("\n", $array);
        } else {
            return implode("\n", array_map(function ($a) {
                return implode("\n", $a);
            }, $array));
        }
    }

    protected function serverError(string $msg, mixed $code = 500): \Illuminate\Http\JsonResponse
    {
        $message = 'SERVER_ERROR';
        $description = 'Internal Server Error';
        if($code != 500) {
            $message = 'ERROR';
            $description = $msg;
        }
        $response = [
            'message' => $message,
            'description' => $description,
            'errors' => [
                'detail' => [$msg]
            ]
        ];
        return response()->json($response, $this->validateErrorCodes($code));
    }

    private function validateErrorCodes($code, $default = HttpResponse::HTTP_INTERNAL_SERVER_ERROR): int
    {
        // Define an array of valid error codes with descriptions
        $validErrorCodes = [
            HttpResponse::HTTP_CONTINUE => 'Continue',
            HttpResponse::HTTP_OK => 'OK',
            HttpResponse::HTTP_CREATED => 'Created',
            HttpResponse::HTTP_BAD_REQUEST => 'Bad Request',
            HttpResponse::HTTP_UNAUTHORIZED => 'Unauthorized',
            HttpResponse::HTTP_FORBIDDEN => 'Forbidden',
            HttpResponse::HTTP_NOT_FOUND => 'Not Found',
            HttpResponse::HTTP_CONFLICT => 'Conflict',
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
            HttpResponse::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error'
        ];

        // Check if the error code is valid
        if (!array_key_exists($code, $validErrorCodes)) {
            return $default;
        } else {
            return $code;
        }
    }


    protected function customError(int $code, string $description, string $msg=null): \Illuminate\Http\JsonResponse
    {
        if ($msg == null) {
            $response = [
                'message' => 'ERROR',
                'description' => $description,
                'errors' => [
                    'detail' => []
                ]
            ];
        }else{
            $response = [
                'message' => 'ERROR',
                'description' => $description,
                'errors' => [
                    'detail' => [$msg]
                ]
            ];
        }
        return response()->json($response, $this->validateErrorCodes($code));
    }

    protected function customErrorWithData(int $code, string $description, array $errors=[], array $data=[]): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => 'ERROR',
            'description' => $description,
            'errors' => [
                'detail' => $errors
            ],
            'data' => $data
        ];
        return response()->json($response, $this->validateErrorCodes($code));
    }

    protected function customSuccessWithData(int $code, string $description, array $data=[]): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => 'SUCCESS',
            'description' => $description,
            'data' => $data
        ];
        return response()->json($response, $this->validateErrorCodes($code, HttpResponse::HTTP_OK));
    }

    protected function notFoundError(string $msg): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => 'ERROR',
            'description' => "Not Found!",
            'errors' => [
                'detail' => [$msg]
            ]
        ];
        return response()->json($response, HttpResponse::HTTP_NOT_FOUND);
    }

    protected function unauthorizedResponse(): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => 'ERROR',
            'description' => "Unauthorized!",
            'errors' => [
                'detail' => ['Unauthorized Access']
            ]
        ];
        return response()->json($response, HttpResponse::HTTP_UNAUTHORIZED);
    }

    protected function forbiddenResponse(): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => 'ERROR',
            'description' => "Forbidden!",
            'errors' => [
                'detail' => ['Forbidden Access']
            ]
        ];
        return response()->json($response, HttpResponse::HTTP_FORBIDDEN);
    }


}
