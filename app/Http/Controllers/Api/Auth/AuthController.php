<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\UserPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'login' => 'required|exists:users,login',
            'password' => 'required'
        ]);
        if ($data->fails()) {
            return errorResponse($data->errors());
        }
        $user = User::where('login', $request->get('login'))->active()->with('roles', function ($query) {
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        if (!$user) {
            return errorResponse(trans('defaultMessages.auth.login_error'), 422);
        }
        if (!Hash::check($request->get('password'), $user->password)) {
            return errorResponse(trans('defaultMessages.auth.pass_error'), 422);
        }
        $user['token'] = $user->createToken('MyLaravelApp')->plainTextToken;

        return successResponse($user)->header('auth_token', $user['token']);

    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'login' => 'required|unique:users,login',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return errorResponse($validator->errors(), 'error', 422);
        }
        $data['login'] = $request->login;
        $data['first_name'] = $request->first_name;
        if (!empty($request->last_name)){
            $data['last_name'] = $request->last_name;
        }
        if (!empty($request->profession)){
            $data['profession'] = $request->profession;
        }
        if (!empty($request->middle_name)){
            $data['middle_name'] = $request->middle_name;
        }
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
//        $role = Role::where('name', 'simpleUser')->first();
//        $user->assignRole($role);
        UserPassword::create([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);

        $user['token'] = $user->createToken('MyLaravelApp')->plainTextToken;

        return successResponse($user)->header('auth_token', $user['token']);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userDetails()
    {
        $user = User::where('id', \auth()->user()->id)->with('roles', function ($query) {
            $query->select('id', 'name')->with('permissions:id,name,description');
        })->first();
        $user['permissions'] =  auth()->user()->getAllPermissions()->pluck('name');
        return successResponse($user);
    }

    public function logout()
    {
        \auth()->user()->tokens()->delete();
        return successResponse("Muvaffaqiyatli tizimidan chiqdingiz");
    }
    public function refreshToken(Request $request)
    {
        $request->user()->tokens()->delete();

        $token['token'] = $request->user()->createToken('api')->plainTextToken;
        return successResponse($token);
    }
}
