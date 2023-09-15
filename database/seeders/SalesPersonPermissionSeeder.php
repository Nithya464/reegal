<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User as AppUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;

class SalesPersonPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //salesperson_permissions_grant

        try
        {
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
 
         $sales_person_role = 'Sales Person#1';
 
         $sales_person_permissions = Config::get('applications.config.permissions.salesperson_permissions_grant');
 
         $sales_person_revokepermissions = Config::get('applications.config.permissions.salesperson_permissions_revoke');
 
         $salesperson_user = AppUser::find(3); // id for the sales person user

         $salesper_role = Role::where('name',$sales_person_role)->get()->first();

         $salesper_role->givePermissionTo($sales_person_permissions);
 
         $salesper_role->revokePermissionTo($sales_person_revokepermissions);
 
         $salesperson_user->assignRole($salesper_role);
 
         }
         catch(\Exception $e)
         {
             echo 'Something went wrong : '.$e->getMessage();
         }

    }
}
