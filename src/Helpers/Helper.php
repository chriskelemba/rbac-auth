<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('sendApiResponse')) {
    function sendApiResponse($result, $message = '', $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('sendApiError')) {
    function sendApiError($message = 'Error', $code = 400, $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}