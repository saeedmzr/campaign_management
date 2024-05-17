<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Info(
 *     description="This is an example API for order management",
 *     version="1.0.0",
 *     title="Order Management API"
 * )
 * @OA\Server(
 *       url="/api/",
 *       description="Base path for all Order CRUD API endpoints"
 *   )
 */
class BaseController extends Controller
{

    /**
     * Returns an error JSON response with the given message, status code, and other details.
     *
     * @param array $data
     * @param string $message The error message
     * @param int $status_code
     * @return JsonResponse
     */
    protected static function errorResponse(array $data = [], string $message = 'server error', int $status_code = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'result' => 'error',
            'message' => $message,
        ], $status_code);
    }

    /**
     * Returns a successful JSON response with the given data and status code.
     *
     * @param array|AnonymousResourceCollection|JsonResource $data The data to include in the response
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    protected static function successResponse(array|AnonymousResourceCollection|JsonResource $data = [], string $message = 'success', int $status_code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'result' => 'success',
            'message' => $message,
        ], $status_code);
    }

}
