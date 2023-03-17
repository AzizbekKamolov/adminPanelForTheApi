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
        return successResponse($data);
    }
    public function index(){
        $users = User::select('id', 'first_name', 'last_name', 'middle_name', 'profession')->with('roles', function ($query){
            $query->with('permissions:id,name');
        })->get();
        return successResponse($users);
    }
    public function create(){
        //
    }
    public function edit($user){
        $data = User::select('id', 'first_name', 'last_name', 'middle_name', 'profession')->where('id', $user)->with('roles', function ($query){
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        if (!$data){
            return errorResponse(trans('defaultMessages.users.not_found'));
        }
        return successResponse($data);
    }

    public function store(Request $request){
        $data = Validator::make($request->all(), [
            'first_name' => 'required',
            'login' => 'required|unique:users',
            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'login' => $request->login,
            'password' => Hash::make($request->password),
        ]);
        UserPassword::create([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);
        $user->syncRoles($request->roles);
        return successResponse($user, trans('defaultMessages.users.create_success'));
    }
    public function update(Request $request, $user){
        $data = Validator::make($request->all(), [
            'first_name' => 'required',
            'login' => "required|unique:users,login,$user",
            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $user = User::find($user);
        if (!$user){
            return errorResponse(trans('defaultMessages.users.not_found'));
        }
        $user->first_name = $request->first_name;
        $user->login = $request->login;
        $user->password = Hash::make($request->password);
        $user->update();


        UserPassword::where('user_id', $user->id)->update([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);
        $user->syncRoles($request->roles);
        return successResponse($user, trans('defaultMessages.users.update_success'));
    }
    public function show($user){
        $data = User::select('id', 'name', 'login')->where('id', $user)->with('roles', function ($query){
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        if (!$data){
            return errorResponse("Ma'lumot topilmadi", 'error', 404);
        }
        $data->password = decrypt($data->userPassword->content);
        return successResponse($data);
    }
    public function destroy($user){
        $data = User::find($user);
        if (!$data){
            return errorResponse(trans('defaultMessages.users.not_found'));
        }
        $data->delete();

        return successResponse($data, trans('defaultMessages.users.success_delete'));
    }
    public function getPassword($user){
        $data = User::with('userPassword')->find($user);
        if (!$data->userPassword){
            return errorResponse(trans('defaultMessages.users.not_found'));
        }
        $result['password'] = decrypt($data->userPassword->content);
        return successResponse($result);

    }
}
