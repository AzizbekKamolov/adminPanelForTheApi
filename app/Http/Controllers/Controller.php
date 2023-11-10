<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDataWithPaginate($data)
    {
        return request()->per_page == 'all' ? $data->get() : (is_int(request()->per_page)) ? $data->paginate(request()->per_page) : $data->paginate();
    }

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
