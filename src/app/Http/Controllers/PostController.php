<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // ✅ バリデーション
        $validated = $request->validate([
            'category' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'tags' => 'nullable|string',
            'comment' => 'nullable|string|max:140',
        ]);

        // ✅ 画像アップロード処理
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // ✅ データベース保存
        Post::create([
            'category' => $validated['category'],
            'image_path' => $imagePath,
            'rating' => $validated['rating'],
            'tags' => $validated['tags'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('home')->with('success', '投稿を保存しました！');
    }
}
