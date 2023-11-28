<?php

namespace App\Http\Controllers\Api\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::query()->select('id', 'name')->orderBy('name', 'ASC')
//            ->with('permissions:id,name')
            ->get();
        return $this->successResponse($roles);
    }
    public function create(){
        //
    }
    public function edit($role){
        $data = Role::select('id', 'name')->where('id', $role)->with('permissions:id,name')->first();
        if (!$data){
            return $this->error(trans('defaultMessages.roles.not_found'), 404);
        }
        return $this->successResponse($data);
    }

    public function store(Request $request){
        $data = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($data->fails()){
            return $this->errorResponse($data->errors());
        }
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api'
        ]);
        $role->syncPermissions($request->permissions);
        return $this->successResponse($role, trans('defaultMessages.roles.create_success'));
    }
    public function update(Request $request, $role){
        $data = Validator::make($request->all(), [
            'name' => "required|unique:roles,name,$role",
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        if ($data->fails()){
            return $this->errorResponse($data->errors());
        }
        $result = Role::where('id', $role)->first();
        if (!$result){
            return $this->error(trans('defaultMessages.roles.not_found'), 404);
        }
        $result->name = $request->name;
        $result->update();
        $result->syncPermissions($request->permissions);
        return $this->successResponse($result, trans('defaultMessages.roles.update_success'));
    }
    public function show($role){
        //
    }
    public function destroy($role){
        $data = Role::find($role);
        if (!$data){
            return $this->error(trans('defaultMessages.roles.not_found'), 404);
        }
        $data->delete();
        return $this->successResponse($data, trans('defaultMessages.roles.success_delete'));
    }
}
