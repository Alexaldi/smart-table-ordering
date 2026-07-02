<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $morningShift = Shift::updateOrCreate(
            ['name' => 'Pagi'],
            [
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
            ]
        );

        $eveningShift = Shift::updateOrCreate(
            ['name' => 'Sore'],
            [
                'start_time' => '16:00:00',
                'end_time' => '23:00:00',
            ]
        );

        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'role' => 'admin',
                'shift_id' => null,
            ],
            [
                'name' => 'Owner',
                'username' => 'owner',
                'email' => 'owner@example.com',
                'password' => 'owner123',
                'role' => 'owner',
                'shift_id' => null,
            ],
            [
                'name' => 'Kasir Pagi',
                'username' => 'kasir',
                'email' => 'kasir@example.com',
                'password' => 'kasir123',
                'role' => 'kasir',
                'shift_id' => $morningShift->id,
            ],
            [
                'name' => 'Kasir Sore',
                'username' => 'kasir_sore',
                'email' => 'kasir.sore@example.com',
                'password' => 'kasir123',
                'role' => 'kasir',
                'shift_id' => $eveningShift->id,
            ],
            [
                'name' => 'Dapur Pagi',
                'username' => 'dapur',
                'email' => 'dapur@example.com',
                'password' => 'dapur123',
                'role' => 'dapur',
                'shift_id' => $morningShift->id,
            ],
            [
                'name' => 'Dapur Sore',
                'username' => 'dapur_sore',
                'email' => 'dapur.sore@example.com',
                'password' => 'dapur123',
                'role' => 'dapur',
                'shift_id' => $eveningShift->id,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                    'shift_id' => $user['shift_id'],
                    'is_active' => true,
                ]
            );
        }
    }
}
