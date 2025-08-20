<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads', 'public');
                $imagePaths[] = $path;
            }
        }

        Post::create([
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            // モデル側 casts(['image_path' => 'array']) に任せる
            'image_path' => $imagePaths,
            'rating' => $request->input('rating'),
            'tags' => $request->input('tags'),
            'comment' => $request->input('comment'),
            'user' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', '投稿を保存しました！');
    }

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load('author');
        // Like件数と、現在ユーザーがLike済みか（liked_by_me）を付与
        $userId = auth()->id();
        $post->loadCount([
            'likedByUsers',
            'likedByUsers as liked_by_me' => function ($q) use ($userId) {
                if ($userId) {
                    $q->where('users.id', $userId);
                } else {
                    // 未ログイン時は0固定
                    $q->whereRaw('1=0');
                }
            },
        ]);
        return view('post.detail', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorizeOwner($post);
        return view('post.edit', compact('post'));
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorizeOwner($post);
        $validated = $request->validated();

        // 既存の画像配列
        $currentImages = is_array($post->image_path)
            ? $post->image_path
            : (json_decode($post->image_path, true) ?? []);

        // 削除指定があれば取り除く
        $delete = (array) $request->input('delete_images', []);
        if (!empty($delete)) {
            $currentImages = array_values(array_filter($currentImages, function ($p) use ($delete) {
                return !in_array($p, $delete, true);
            }));
        }

        // 新規アップがあれば追加（最大4枚を上限）
        $selectedNewIndex = null;
        $selectedNewPath = null;
        if ($request->hasFile('images')) {
            $coverParam = $request->input('cover');
            if ($coverParam && str_starts_with($coverParam, 'new:')) {
                $selectedNewIndex = (int) substr($coverParam, 4);
            }

            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('uploads', 'public');
                $currentImages[] = $path;
                if ($selectedNewIndex !== null && $i === $selectedNewIndex) {
                    $selectedNewPath = $path;
                }
            }
        }
        // 上限3枚に丸める
        $currentImages = array_slice($currentImages, 0, 3);

        // カバー画像（トップ画像）の指定に従い並び替え
        $cover = $request->input('cover');
        if ($cover) {
            if ($selectedNewPath) {
                // 指定された新規画像を先頭に
                $currentImages = array_values(array_diff($currentImages, [$selectedNewPath]));
                array_unshift($currentImages, $selectedNewPath);
            } elseif (in_array($cover, $currentImages, true)) {
                // 既存から選択
                $currentImages = array_values(array_diff($currentImages, [$cover]));
                array_unshift($currentImages, $cover);
            }
        }

        $post->title = $validated['title'];
        $post->category = $validated['category'];
        $post->image_path = $currentImages;
        $post->rating = $validated['rating'];
        $post->tags = $validated['tags'] ?? null;
        $post->comment = $validated['comment'] ?? null;
        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', '更新しました');
    }

    public function destroy(Post $post)
    {
        $this->authorizeOwner($post);
        $post->delete(); // ソフトデリート
        return redirect()->route('home')->with('success', '記録を削除しました');
    }

    private function authorizeOwner(Post $post): void
    {
        if (auth()->id() !== (int) $post->user) {
            abort(403);
        }
    }
}
