<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class InventoryManagerPermissionSeeder extends Seeder
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

        $inv_manager_role = 'Inventory Manager#1';

        $Inv_Mangr_permissions = Config::get('applications.config.permissions.inv_manager_permissions_grant');

        $Inv_Mangr_revokepermissions = Config::get('applications.config.permissions.inv_manager_permissions_revoke');

        $invmag_user = AppUser::find(8); // id for the inventory manager user

        $Inv_Mangr_permissions_arr = array_column($Inv_Mangr_permissions,'name');

        $Inv_Mangr_revokepermissions_arr = array_column($Inv_Mangr_revokepermissions,'name');

        $invmag_role = Role::where('name',$inv_manager_role)->get()->first();

        $invmag_role->givePermissionTo($Inv_Mangr_permissions_arr);

        $invmag_role->revokePermissionTo($Inv_Mangr_revokepermissions_arr);

        $invmag_user->assignRole($invmag_role);

        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }

    }
}
