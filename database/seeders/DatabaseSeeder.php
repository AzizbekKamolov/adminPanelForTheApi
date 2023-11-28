<?php

namespace Database\Seeders;

use App\Models\Management\Role;
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
        $role = Role::query()->where('name', 'superAdmin')->first();
        $permission = \App\Models\Management\Permission::query()->select('id')->get()->toArray();
        $role->syncPermissions($permission);
        User::query()->create([
           "first_name" => "Admin",
           "last_name" => "Admin",
           "phone_number" => "998901111111",
           "password" => bcrypt("admin"),
        ]);
        $user = User::query()->where('id', 1)->first();
        $user->assignRole($role);
    }
}
