<?php

namespace Database\Seeders;
use App\Models\User; // Make sure the namespace and model import are correct
use App\User as AppUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
       
        $users = Config::get('applications.config.user');
        foreach ($users as $user) {
            try {
                AppUser::create([
                    'user_type' => 'user',
                    'first_name' => $user['first_name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password']),
                    'business_id' => null // due to foreign key  constraint create with business_id as null then removes nullable in table structure and set 1
                ]);
            } catch (\Exception $e) {
                // Handle the exception and display an error message
                echo "Error creating user: " . $e->getMessage() . "\n";
            }
        }
    }
}

