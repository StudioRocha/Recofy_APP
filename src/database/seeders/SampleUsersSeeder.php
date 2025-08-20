<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SampleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 件数は環境変数で指定（例）SEED_SAMPLE_USER_COUNT=50
        // 未指定時は10件
        $envCount = (int) env('SEED_SAMPLE_USER_COUNT', 0);
        $count = $envCount > 0 ? $envCount : 10;

        User::factory()->count($count)->create();
    }
}


