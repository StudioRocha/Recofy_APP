<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'user')) {
            Schema::table('posts', function (Blueprint $table) {
                // users.id と紐づく外部キー（NULL不可・カスケード削除）
                $table->foreignId('user')->constrained('users')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('posts', 'user')) {
            Schema::table('posts', function (Blueprint $table) {
                // 外部キーが存在しない場合に備え、配列指定のdropForeignを試みる
                try {
                    $table->dropForeign(['user']);
                } catch (\Throwable $e) {
                    // 既に外部キーが無い場合は無視
                }
                $table->dropColumn('user');
            });
        }
    }
};


