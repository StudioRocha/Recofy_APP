<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePostRequest;

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
            'image_path' => json_encode($imagePaths),
            'rating' => $request->input('rating'),
            'tags' => $request->input('tags'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->route('home')->with('success', '投稿を保存しました！');
    }
}
