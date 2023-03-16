<?php

if (!function_exists('successResponse')){
    function successResponse($data, $message = 'success', $code = 200){
        return response()->json([
            'data' => $data,
            'status' => 1,
            'message' => $message,
            'code' => $code
        ], $code)->withHeaders(['statusCode' => $code]);
    }
}
if (!function_exists('errorResponse')){
    function errorResponse($data, $message = 'error', $code = 422){
        return response()->json([
            'data' => [
                'errors' =>$data
            ],
            'status' => 0,
            'message' => $message,
            'code' => $code
        ], $code)->withHeaders(['statusCode' => $code]);
    }
}
