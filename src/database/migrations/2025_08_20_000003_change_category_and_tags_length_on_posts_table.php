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
            DB::statement('ALTER TABLE `posts` MODIFY `category` VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE `posts` MODIFY `tags` VARCHAR(50) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN category TYPE VARCHAR(50)');
            DB::statement('ALTER TABLE posts ALTER COLUMN category SET NOT NULL');
            DB::statement('ALTER TABLE posts ALTER COLUMN tags TYPE VARCHAR(50)');
            // tagsはNULL許容のまま
        } else {
            // SQLite等は厳密な長さチェックが効かないため、アプリ層でバリデーション
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `posts` MODIFY `category` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `posts` MODIFY `tags` VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN category TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE posts ALTER COLUMN category SET NOT NULL');
            DB::statement('ALTER TABLE posts ALTER COLUMN tags TYPE VARCHAR(255)');
        } else {
            // no-op
        }
    }
};


