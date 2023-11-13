<?php


namespace App\Traits;


trait AllResponse
{
    public function success($message = 'success', $code = 200)
    {
        return response()->json([
            "status" => 1,
            "message" => $message,
        ])->withHeaders(['code' => $code]);
    }

    public function successResponse($data = [], $message = 'success', $code = 200)
    {
        return response()->json([
            "data" => $data,
            "status" => 1,
            "message" => $message,
        ])->withHeaders(['code' => $code]);
    }

    public function error($message = 'error', $code = 422)
    {
        return response()->json([
            "status" => 0,
            "message" => $message,
        ])->withHeaders(['code' => $code]);
    }

    public function errorResponse($data = [], $message = 'error', $code = 422)
    {
        return response()->json([
            "errors" => $data,
            "status" => 0,
            "message" => $message,
        ])->withHeaders(['code' => $code]);
    }
}
