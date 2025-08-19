<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 既存の二重JSONやエスケープを正規化
        $posts = DB::table('posts')->select('id', 'image_path')->get();
        foreach ($posts as $post) {
            $raw = $post->image_path;
            if ($raw === null || $raw === '') { continue; }

            $paths = null;
            // 1回目の decode
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $paths = $decoded;
            } elseif (is_string($decoded)) {
                // 二重JSONのケース
                $decoded2 = json_decode($decoded, true);
                if (is_array($decoded2)) { $paths = $decoded2; }
            }

            // どれにも当てはまらない場合、カンマ区切りや単一文字列として扱う
            if ($paths === null) {
                if (is_string($raw)) {
                    // 先頭と末尾の引用符を除去
                    $trimmed = trim($raw, "\"' ");
                    // JSONエスケープが多重に入っている場合のバックスラッシュ除去
                    $trimmed = str_replace('\\\\', '\\', $trimmed);
                    // 単一パスとして格納
                    $paths = [$trimmed];
                } else {
                    $paths = [];
                }
            }

            // public/ や storage/ を除去
            $paths = array_values(array_filter(array_map(function ($p) {
                if (!is_string($p)) return null;
                $p = preg_replace('#^public/#', '', $p);
                $p = preg_replace('#^storage/#', '', $p);
                return $p;
            }, $paths), fn($v) => $v !== null && $v !== ''));

            DB::table('posts')->where('id', $post->id)->update([
                'image_path' => json_encode($paths, JSON_UNESCAPED_SLASHES),
            ]);
        }
    }

    public function down(): void
    {
        // 変更を戻さない（非破壊移行）
    }
};


