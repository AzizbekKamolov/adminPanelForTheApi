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
            return errorResponse(trans('defaultMessages.permissions.not_found'), 'error', 404);
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
            'name' => $request->name,
            'guard_name' => 'api'
        ]);
        return successResponse($permission, trans('defaultMessages.permissions.create_success'));
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
            return errorResponse(trans('defaultMessages.permissions.not_found'));
        }
        $result->name = $request->name;
        $result->update();
        return successResponse($result, trans('defaultMessages.permissions.update_success'));
    }
    public function show($permission){
        //
    }
    public function destroy($permission){
        $data = Permission::find($permission);
        if (!$data){
            return errorResponse(trans('defaultMessages.permissions.not_found'), 'error', 404);
        }
        $data->delete();
        return successResponse(trans('defaultMessages.permissions.success_delete'));
    }
}
