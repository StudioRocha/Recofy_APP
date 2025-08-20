<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $userId = (int) auth()->id();
        if (!$userId) {
            abort(401);
        }
        $liked = $post->likedByUsers()->where('users.id', $userId)->exists();
        if ($liked) {
            $post->likedByUsers()->detach($userId);
        } else {
            $post->likedByUsers()->syncWithoutDetaching([$userId]);
        }

        $count = $post->likedByUsers()->count();
        return response()->json(['ok' => true, 'liked' => !$liked, 'count' => $count]);
    }
}


