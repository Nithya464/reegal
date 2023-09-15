<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class PackerPermissionSeeder extends Seeder
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

        $packer_role = 'Packer#1';
        
        $Packer_permissions = Config::get('applications.config.permissions.packer_permissions_grant');

        $Packer_revokepermissions = Config::get('applications.config.permissions.packer_permissions_revoke');

        $packer_user = AppUser::find(6); // id for the packer user 

        $packer_role = Role::where('name',$packer_role)->get()->first();

        $packer_role->givePermissionTo($Packer_permissions);

        $packer_role->revokePermissionTo($Packer_revokepermissions);

        $packer_user->assignRole($packer_role);
        }
        catch(\Exception $e)
        {
            echo 'Something went wrong : '.$e->getMessage();
        }

    }
}
