<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
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
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $data['user'] = Auth::user();
            $data['token'] =  $data['user']->createToken('MyLaravelApp')-> accessToken;
            return successResponse($data);
        }
        else{
            return successResponse([], "Login yoki parol xato", 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return errorResponse($validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token =  $user->createToken('MyLaravelApp')->accessToken;
        cookie('auth_token', $token);
        return response()->json([
            'user' => $user,
            'token' => $token,
            'code' => 200,
            'message' => 'success'
        ])->withCookie('auth_token');

    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userDetails()
    {
        $user = User::where('id', \auth()->user()->id)->with('roles', function ($query){
            $query->select('id', 'name')->with('permissions:id,name');
        })->first();
        return successResponse($user);
    }
    public function logout(){
        $user = Auth::user()->token();
        $user->revoke();
        return successResponse([], "Muvaffaqiyatli tizimidan chiqdingiz");
    }
}
