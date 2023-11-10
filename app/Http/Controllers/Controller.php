<?php

namespace App\Http\Controllers;

use App\Variables\AllResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AllResponse;

    public function getDataWithPaginate($data)
    {
        return request()->per_page == 'all' ? $data->get() : (is_int(request()->per_page)) ? $data->paginate(request()->per_page) : $data->paginate();
    }
}
