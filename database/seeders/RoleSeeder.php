<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        $roles = Config::get('applications.config.role');
        foreach ($roles as $role) {
            Role::create([
                'name' => $role.'#1',
                'guard_name' => 'web',
                'business_id' => 1,
                'is_default' => 1,
                'is_service_staff' => 0,
                'is_delete' => '1',
            ]);
        }
    }
}
