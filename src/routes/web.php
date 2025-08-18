<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PostController;

// ✅ トップページ
Route::get('/', [IndexController::class, 'index'])->name('home');

// ✅ 投稿フォーム（仮：今後削除予定 or モーダル）
Route::get('/posts/create', function () {
    return '投稿作成ページ（仮）';
})->name('posts.create');

// ✅ 投稿一覧 → PostController に差し替え済み
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// ✅ 投稿保存（フォームの送信先）
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');


// ✅ マイページ（仮）
Route::get('/mypage', function () {
    return 'マイページ（仮）';
})->name('mypage');

// ✅ お気に入り一覧（仮）
Route::get('/favorites', function () {
    return 'お気に入り一覧（未実装）';
})->name('favorites');

// ✅ 通知（仮）
Route::get('/notifications', function () {
    return '通知（未実装）';
})->name('notifications');

// ✅ 記録一覧（仮）
Route::get('/records', function () {
    return '記録一覧ページ（未実装）';
})->name('records');