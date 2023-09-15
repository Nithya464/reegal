<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;

class InventoryReceiverPermissionSeeder extends Seeder
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

        $inv_receiver_role = 'Inventory Receiver#1';
        
        $Inv_Recvr_permissions = Config::get('applications.config.permissions.inv_receiver_permissions_grant');

        $Inv_Recvr_revokepermissions = Config::get('applications.config.permissions.inv_receiver_permissions_revoke');

        $invrec_user = AppUser::find(7); // id for the inventory receiver user 

        $invrec_role = Role::where('name',$inv_receiver_role)->get()->first();

        $invrec_role->givePermissionTo($Inv_Recvr_permissions);

        $invrec_role->revokePermissionTo($Inv_Recvr_revokepermissions);

        $invrec_user->assignRole($invrec_role);

        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }
    }
}
