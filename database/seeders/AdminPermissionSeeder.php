<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try
        {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin_user_role = Config::get('applications.config.admin_user_role');

        $admin_user = AppUser::find(1); //default id for the super admin is 1

        $admin_role = Role::where('name',$admin_user_role)->get()->first();

        $admin_role->givePermissionTo(Permission::all());

        $admin_user->assignRole($admin_user_role);
        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }
    }
}
