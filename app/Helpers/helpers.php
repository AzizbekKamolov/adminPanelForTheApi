<?php

if (!function_exists('successResponse')){
    function successResponse($data, $message = 'success', $code = 200){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code
        ]);
    }
}
if (!function_exists('errorResponse')){
    function errorResponse($data, $message = 'error', $code = 400){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code
        ]);
    }
}
