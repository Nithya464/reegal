<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class WarehouseManagerPermissionSeeder extends Seeder
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

        $warehousemg_role = 'Warehouse Manager#1';

        $WarHs_Mangr_permissions = Config::get('applications.config.permissions.warehs_manager_permissions_grant');

        $WarHs_Mangr_revokepermissions = Config::get('applications.config.permissions.warehs_manager_permissions_revoke');

        $warehousemg_user = AppUser::find(2); // id for the warehouse manager user

        $warhsmag_role = Role::where('name',$warehousemg_role)->get()->first();

        $warhsmag_role->givePermissionTo($WarHs_Mangr_permissions);

        $warhsmag_role->revokePermissionTo($WarHs_Mangr_revokepermissions);

        $warehousemg_user->assignRole($warhsmag_role);

        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }
        
    }
}

?>
