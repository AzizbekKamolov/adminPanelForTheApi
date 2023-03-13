<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert(config('myVariables.permissions'));
        DB::table('roles')->insert(config('myVariables.roles'));
        $role = Role::where('name', 'superAdmin')->first();
        $permission = \App\Models\Admin\Permission::select('id')->get()->toArray();
        $role->syncPermissions($permission);
        $user = User::where('id', 1)->first();
        $user->assignRole($role);
    }
}
