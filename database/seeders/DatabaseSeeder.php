<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'fullName' =>'Kaleb Tilahun Tesfaye',
            'email' => 'Kaleb@gmail.com',
            'password' =>bcrypt('12345678'),
            'role_id'=>1
        ]);
        DB::table('users')->insert([
            'fullName' =>'Isaak Tilahun Tesfaye',
            'email' => 'Isaak@gmail.com',
            'password' =>bcrypt('12345678'),
            'role_id'=>2
        ]);
        DB::table('users')->insert([
            'fullName' =>'Josh Tilahun Tesfaye',
            'email' => 'Josh@gmail.com',
            'password' =>bcrypt('12345678'),
            'role_id'=>4
        ]);
    }
}
