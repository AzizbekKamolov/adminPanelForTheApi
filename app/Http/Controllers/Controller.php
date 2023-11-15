<?php

namespace App\Http\Controllers;

use App\Traits\AllResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AllResponse;

    public function getDataWithPaginate($data)
    {
        if (request()->per_page == 'all'){
            return ['data' => $data->get()];
        }elseif (is_numeric(request()->per_page)){
            return $data->paginate(request()->per_page);
        }
        return $data->paginate();
    }
}
