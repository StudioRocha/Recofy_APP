<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ここに管理者/作業者を追記するだけでOK
        $adminUsers = [
            [
                'email' => 'dev@example.com',
                'name' => 'Developer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            // 例)
            // [
            //     'email' => 'staff1@example.com',
            //     'name' => 'Staff 1',
            //     'password' => Hash::make('change-me'),
            //     'email_verified_at' => now(),
            // ],
        ];

        foreach ($adminUsers as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'email_verified_at' => $user['email_verified_at'] ?? now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}


