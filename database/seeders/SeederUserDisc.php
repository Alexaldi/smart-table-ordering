<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeederUserDisc extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            'name'       => 'Admin',
            'username'   => 'admin',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'shift_id'   => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}