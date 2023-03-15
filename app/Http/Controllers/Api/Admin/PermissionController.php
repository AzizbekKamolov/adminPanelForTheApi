<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index(){
        $permissions = Permission::select('id', 'name')->orderBy('name', 'ASC')->get();
        return successResponse($permissions);
    }
    public function create(){
    //
    }
    public function edit($permission){
        $data = Permission::find($permission);
        if (!$data){
            $a[$permission] = 'Ma\'lumot topilmadi';
            return errorResponse($a);
        }
        return successResponse($data);
    }

    public function store(Request $request){
        $data = Validator::make($request->all(), [
            'name' => 'required|unique:permissions'
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $permission = Permission::create([
            'name' => $data->validate()['name'],
            'guard_name' => 'api'
        ]);
        return successResponse($permission);
    }
    public function update(Request $request, $permission){
        $data = Validator::make($request->all(), [
            'name' => "required|unique:permissions,name,$permission"
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $result = Permission::select('id', 'name')->where('id', $permission)->first();
        if (!$result){
            $a[$permission] = 'Ma\'lumot topilmadi';
            return errorResponse($a);
        }
        $result->name = $request->name;
        $result->update();
        return successResponse($result);
    }
    public function show($permission){
        //
    }
    public function destroy($permission){
        $data = Permission::find($permission);
        if (!$data){
            $a[$permission] = 'Ma\'lumot topilmadi';
            return errorResponse($a);
        }
        $data->delete();
        return successResponse($data);
    }
}
