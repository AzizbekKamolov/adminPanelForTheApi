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
        $users = User::select('id', 'name', 'email')->orderBy('name', 'ASC')->with('roles', function ($query){
            $query->select('id', 'name')->with('permissions:id,name');
        })->get();
        return successResponse($users);
    }
    public function create(){
        //
    }
    public function edit($user){
        $data = User::select('id', 'name', 'email')->where('id', $user)->with('roles', function ($query){
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        if (!$data){
            $a[$user] = 'Ma\'lumot topilmadi';
            return errorResponse($a);
        }
        $data->password = decrypt($data->userPassword->content);
        return successResponse($data);
    }

    public function store(Request $request){
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        UserPassword::create([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);
        $user->syncRoles($request->roles);
        return successResponse($user);
    }
    public function update(Request $request, $user){
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'email' => "required|unique:users,email,$user",
            'password' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        if ($data->fails()){
            return errorResponse($data->errors());
        }
        $user = User::find($user);
        if (!$user){
            $a[] ="Ma'lumot topilmadi";
            return errorResponse($a);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();


        UserPassword::where('user_id', $user->id)->update([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);
        $user->syncRoles($request->roles);
        return successResponse($user);
    }
    public function show($user){
        $data = User::select('id', 'name', 'email')->where('id', $user)->with('roles', function ($query){
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
            $a[$user] = 'Ma\'lumot topilmadi';
            return errorResponse($a);
        }
        $data->delete();
        return successResponse($data);
    }
}
