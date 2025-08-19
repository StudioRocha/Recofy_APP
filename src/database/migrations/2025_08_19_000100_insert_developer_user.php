<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

return new class extends Migration {
    public function up(): void
    {
        // 既に存在する場合は更新、無ければ作成
        DB::table('users')->updateOrInsert(
            ['email' => 'dev@example.com'],
            [
                'name' => 'Developer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'dev@example.com')->delete();
    }
};


