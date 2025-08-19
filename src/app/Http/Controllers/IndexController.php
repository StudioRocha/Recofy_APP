<?php


namespace App\Http\Controllers;

use App\Models\Post;

class IndexController extends Controller
{
    public function index()
    {
        $posts = Post::with('author:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedPosts = $posts->groupBy(function ($post) {
            return optional($post->created_at)->format('Y/m/d') ?? '日付不明';
        });

        return view('index', [
            'groupedPosts' => $groupedPosts,
        ]);
    }
}