<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class AccountManagerPermissionSeeder extends Seeder
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
        // Account Manager#1
            
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $accmg_role = 'Account Manager#1';

        $Acc_Mangr_permissions = Config::get('applications.config.permissions.acc_manager_permissions_grant');

        $Acc_Mangr_revokepermissions = Config::get('applications.config.permissions.acc_manager_permissions_revoke');

        $accmngr_user = AppUser::find(9); // id for the account manager user

        $accmngr_role = Role::where('name',$accmg_role)->get()->first();

        $accmngr_role->givePermissionTo($Acc_Mangr_permissions);

        $accmngr_role->revokePermissionTo($Acc_Mangr_revokepermissions);

        $accmngr_user->assignRole($accmngr_role);

        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }
    }
}
