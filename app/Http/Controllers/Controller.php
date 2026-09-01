<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Every AJAX endpoint in Digino answers with the same envelope so the
     * front-end helper can treat all of them identically.
     */
    protected function ok(string $message = '', array $payload = [], int $status = 200): JsonResponse
    {
        return response()->json(['ok' => true, 'message' => $message] + $payload, $status);
    }

    protected function fail(string $message, array $payload = [], int $status = 422): JsonResponse
    {
        return response()->json(['ok' => false, 'message' => $message] + $payload, $status);
    }
}
