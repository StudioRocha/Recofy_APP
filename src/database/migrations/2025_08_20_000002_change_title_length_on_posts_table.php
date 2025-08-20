<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `posts` MODIFY `title` VARCHAR(30) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN title TYPE VARCHAR(30)');
            DB::statement('ALTER TABLE posts ALTER COLUMN title SET NOT NULL');
        } else {
            // SQLite 等は VARCHAR 長の厳密な制約が効かないため、アプリ側のバリデーションで担保
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `posts` MODIFY `title` VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN title TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE posts ALTER COLUMN title SET NOT NULL');
        } else {
            // no-op
        }
    }
};


