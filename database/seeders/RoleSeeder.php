<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'roleName' =>'Admin',
            'isAdmin'=>true
        ]
    );
    DB::table('roles')->insert([
        'roleName' =>'Editor',
        'isAdmin'=>true
    ]);
    DB::table('roles')->insert([
        'roleName' =>'Moderator',
        'isAdmin'=>true
    ]);
    DB::table('roles')->insert([
        'roleName' =>'User',
        'isAdmin'=>false
    ]);
    }
}
