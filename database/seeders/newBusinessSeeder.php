<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class newBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Business::create([
            'name' => 'Reegal',
            'currency_id' => 1,
            'owner_id' => null   // due to foreign key  constraint create with owner_id as null then removes nullable in table structure
        ]);
    }
}
