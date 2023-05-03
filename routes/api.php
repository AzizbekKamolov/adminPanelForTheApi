<?php

use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
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
        return errorResponse(["message" => trans('defaultMessages.notAuthorized')], "error", 401);
    })->name('notAuthorized');


    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user-details', [AuthController::class, 'userDetails']);

        //Permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->can('permission.index');
        Route::post('/permissions', [PermissionController::class, 'store'])->can('permission.store');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->can('permission.edit');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->can('permission.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->can('permission.delete');

        //Roles
        Route::get('/roles', [RoleController::class, 'index'])->can('role.index');
        Route::post('/roles', [RoleController::class, 'store'])->can('role.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->can('role.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->can('role.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->can('role.delete');

        //Users
        Route::get('/users', [UserController::class, 'index'])->can('user.index');
        Route::post('/users', [UserController::class, 'store'])->can('user.store');
        Route::get('/users/{user}/show', [UserController::class, 'show'])->can('user.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->can('user.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->can('user.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->can('user.delete');
        Route::get('users/get-permissions', [UserController::class, 'getPermissions']);
        Route::get('/get-password/{user?}', [UserController::class, 'getPassword'])->can('user.getPassword');

    });

});
