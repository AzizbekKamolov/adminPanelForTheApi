<?php

use App\Http\Controllers\Api\Admin\Management\PermissionController;
use App\Http\Controllers\Api\Admin\Management\RoleController;
use App\Http\Controllers\Api\Admin\Management\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('SetLocale')->group(function () {
    Route::get('notAuthorized', function () {
        $res = new \App\Http\Controllers\Controller();
        return $res->error(trans('defaultMessages.notAuthorized'),401);
    })->name('notAuthorized');


    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user-details', [AuthController::class, 'userDetails']);

        //Permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->can('permission.index');
        Route::post('/permission', [PermissionController::class, 'store'])->can('permission.store');
        Route::get('/permission/{permission}', [PermissionController::class, 'edit'])->can('permission.edit');
        Route::put('/permission/{permission}', [PermissionController::class, 'update'])->can('permission.update');
        Route::delete('/permission/{permission}', [PermissionController::class, 'destroy'])->can('permission.delete');

        //Roles
        Route::get('/roles', [RoleController::class, 'index'])->can('role.index');
        Route::post('/role', [RoleController::class, 'store'])->can('role.store');
        Route::get('/role/{role}', [RoleController::class, 'edit'])->can('role.edit');
        Route::put('/role/{role}', [RoleController::class, 'update'])->can('role.update');
        Route::delete('/role/{role}', [RoleController::class, 'destroy'])->can('role.delete');

        //Users
        Route::get('/users', [UserController::class, 'index'])->can('user.index');
        Route::post('/user', [UserController::class, 'store'])->can('user.store');
        Route::put('/user-active/{id}', [UserController::class, 'userActive'])->can('user.update');
        Route::get('/user/{user}', [UserController::class, 'edit'])->can('user.edit');
        Route::put('/user/{user}', [UserController::class, 'update'])->can('user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->can('user.delete');
        Route::get('/user-get-permissions', [UserController::class, 'getPermissions']);
        Route::get('/get-password/{user?}', [UserController::class, 'getPassword'])->can('user.getPassword');

    });

});
