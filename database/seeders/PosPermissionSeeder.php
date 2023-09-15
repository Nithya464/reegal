<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class PosPermissionSeeder extends Seeder
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

        $pos_role = 'POS#1';
        
        $pos_permissions = Config::get('applications.config.permissions.pos_permissions_grant');

        $pos_revokepermissions = Config::get('applications.config.permissions.pos_permissions_revoke');

        $pos_user = AppUser::find(5); // id for the pos user 

        $pos_role = Role::where('name',$pos_role)->get()->first();

        $pos_role->givePermissionTo($pos_permissions);

        $pos_role->revokePermissionTo($pos_revokepermissions);

        $pos_user->assignRole($pos_role);
        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }
    }
}
