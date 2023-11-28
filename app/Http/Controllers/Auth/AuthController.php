<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Management\Role;
use App\Models\Management\UserPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone_number' => 'required|exists:users,phone_number',
            'password' => 'required'
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors());
        }
        $user = User::where('phone_number', str_replace('+', '', $request->get('phone_number')))->active()->with('roles', function ($query) {
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        if (!$user) {
            return errorResponse(trans('defaultMessages.auth.login_error'), 422);
        }
        if (!Hash::check($request->get('password'), $user->password)) {
            return errorResponse(trans('defaultMessages.auth.pass_error'), 422);
        }
        $user['token'] = $user->createToken('MyLaravelApp')->plainTextToken;

        return $this->successResponse($user)->header('auth_token', $user['token']);

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
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return errorResponse($validator->errors(), 'error', 422);
        }
        $data['phone_number'] = str_replace('+', '', $request->get('phone_number'));
        $data['first_name'] = $request->first_name;
        if (!empty($request->last_name)){
            $data['last_name'] = $request->last_name;
        }
        if (!empty($request->middle_name)){
            $data['middle_name'] = $request->middle_name;
        }
        $data['password'] = Hash::make($request->password);
        $user = User::query()->create($data);
//        $role = Role::where('name', 'simpleUser')->first();
//        $user->assignRole($role);
        UserPassword::query()->create([
            'user_id' => $user->id,
            'content' => encrypt($request->password)
        ]);

        $user['token'] = $user->createToken('MyLaravelApp')->plainTextToken;

        return $this->successResponse($user);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userDetails()
    {
        $user = User::where('id', \auth()->user()->id)->with('roles', function ($query) {
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        $user['permissions'] =  auth()->user()->getAllPermissions()->pluck('name');
        return $this->successResponse($user);
    }

    public function logout()
    {
        \auth()->user()->tokens()->delete();
        return $this->success("Muvaffaqiyatli tizimidan chiqdingiz");
    }
    public function refreshToken(Request $request)
    {
        $request->user()->tokens()->delete();

        $token['token'] = $request->user()->createToken('api')->plainTextToken;
        return $this->successResponse($token);
    }
}
