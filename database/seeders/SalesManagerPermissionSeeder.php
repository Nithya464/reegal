<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class SalesManagerPermissionSeeder extends Seeder
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
 
         $sales_mngr_role = 'Sales Manager#1';
 
         $sales_Mangr_permissions = Config::get('applications.config.permissions.sales_manager_permissions_grant');
 
         $sales_Mangr_revokepermissions = Config::get('applications.config.permissions.sales_manager_permissions_revoke');
 
         $salesmngr_user = AppUser::find(4); // id for the sales manager user
         
         $salesmng_role = Role::where('name',$sales_mngr_role)->get()->first();

         $salesmng_role->givePermissionTo($sales_Mangr_permissions);
 
         $salesmng_role->revokePermissionTo($sales_Mangr_revokepermissions);
 
         $salesmngr_user->assignRole($salesmng_role);
 
         }
         catch(\Exception $e)
         {
             echo 'Something went wrong : '.$e->getMessage();
         }
        
    }
}
