<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getPermissions(){
        $users = auth()->user()->getAllPermissions()->toArray();
        $data = [];
        foreach ($users as $user){
            $a = explode('.',$user['name']);
            $data[$a[0].'s'][] = $user['name'];
        }
        return $this->successResponse($data);
    }
    public function index(){
        $users = User::query()->select('id', 'first_name', 'last_name', 'middle_name', 'active')->with('roles:id,name');
        return $this->successResponse($this->getDataWithPaginate($users));
    }
    public function create(){
        //
    }
    public function edit($user){
        $data = User::query()->where('id', $user)->with('roles')->first();
        if (!$data){
            return $this->error(trans('defaultMessages.users.not_found'), 404);
        }
        return $this->successResponse($data);
    }

    public function store(Request $request){
        $data = Validator::make($request->all(), [
            'first_name' => 'required',
            'phone_number' => 'required|unique:users',
            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return $this->errorResponse($data->errors());
        }
        $result['phone_number'] = $request->phone_number;
        $result['first_name'] = $request->first_name;
        $result['password'] = Hash::make($request->password);
        if ($request->last_name){
            $result['last_name'] = $request->last_name;
        }
        if ($request->middle_name){
            $result['middle_name'] = $request->middle_name;
        }
        $user = User::query()->create($result);
        UserPassword::query()->create([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);
        $user->syncRoles($request->roles);
        return $this->success(trans('defaultMessages.users.create_success'));
    }
    public function update(Request $request, $id){
        $data = Validator::make($request->all(), [
            'first_name' => 'required',
            'phone_number' => "required|unique:users,phone_number,$id",
//            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return $this->errorResponse($data->errors());
        }
        $user = User::query()->find($id);
        if (!$user){
            return $this->errorResponse(trans('defaultMessages.users.not_found'), 404);
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->phone_number = $request->phone_number;
        if ($request->has('password')){
            $user->password = Hash::make($request->password);
            UserPassword::where('user_id', $user->id)->update([
                'user_id' => $user->id,
                'content' => encrypt($request->password)
            ]);
        }
        $user->update();
        $user->syncRoles($request->roles);
        return $this->success(trans('defaultMessages.users.update_success'));
    }
    public function userActive(Request $request, $id){
        $user = User::find($id);
        if (!$user){
            return $this->error(trans('defaultMessages.users.not_found'),404);
        }
        $data = Validator::make($request->all(), [
            'active' => 'required|boolean',
        ]);
        if ($data->fails()){
            return $this->errorResponse($data->errors());
        }
        $user->active = $request->active;
        $user->update();
        return $this->successResponse($user, trans('defaultMessages.users.update_success'));
    }
    public function destroy($user){
        $data = User::find($user);
        if (!$data){
            return $this->error(trans('defaultMessages.users.not_found'),404);
        }
        $data->delete();

        return $this->success(trans('defaultMessages.users.success_delete'));
    }

    public function show($id)
    {
        //
    }
    public function getPassword($user){
        $data = UserPassword::query()->where('user_id', $user)->first();
        if (!$data){
            return $this->error(trans('defaultMessages.users.not_found'), 404);
        }
        $result['password'] = decrypt($data->content);
        return $this->successResponse($result);

    }
}
