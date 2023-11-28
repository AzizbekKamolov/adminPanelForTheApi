<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::select('id', 'name')->orderBy('name', 'ASC')->get();
        return $this->successResponse($permissions);
    }

    public function create()
    {
        //
    }

    public function edit($permission)
    {
        $data = Permission::find($permission);
        if (!$data) {
            return $this->error(trans('defaultMessages.permissions.not_found'), 404);
        }
        return $this->successResponse($data);
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required|unique:permissions'
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors());
        }
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->description = $request->get('description');
        $permission->guard_name = 'api';
        $permission->save();
        return $this->successResponse($permission, trans('defaultMessages.permissions.create_success'));
    }

    public function update(Request $request, $permission)
    {
        $data = Validator::make($request->all(), [
            'name' => "required|unique:permissions,name,$permission"
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors());
        }
        $result = Permission::query()->where('id', $permission)->first();
        if (!$result) {
            return $this->error(trans('defaultMessages.permissions.not_found'), 404);
        }
        $result->name = $request->name;
        $result->description = $request->description;
        $result->update();
        return $this->successResponse($result, trans('defaultMessages.permissions.update_success'));
    }

    public function show($permission)
    {
        //
    }

    public function destroy($permission)
    {
        $data = Permission::query()->find($permission);
        if (!$data) {
            return $this->error(trans('defaultMessages.permissions.not_found'), 404);
        }
        $data->delete();
        return $this->success(trans('defaultMessages.permissions.success_delete'));
    }
}
