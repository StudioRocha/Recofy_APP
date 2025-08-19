<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // users.id と紐づく外部キー（NULL不可・カスケード削除）
            $table->foreignId('user')->constrained('users')->cascadeOnDelete();
            // 既存インデックス命名規則に合わせてユニークでないインデックスは自動付与される
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user']);
            $table->dropColumn('user');
        });
    }
};


