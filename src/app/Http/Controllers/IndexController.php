<?php


namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $currentCategory = $request->query('category');
        $currentUserId = Auth::id();
        $isAllView = $request->query('view') === 'all';

        // みんなの記録（最新1日分のみ表示）
        $allQuery = Post::with('author:id,name')
            ->orderBy('created_at', 'desc');
        if (!empty($currentCategory)) {
            $allQuery->where('category', $currentCategory);
        }
        $allPosts = $allQuery->get();
        $groupedAllAll = $allPosts->groupBy(function ($post) {
            return optional($post->created_at)->format('Y/m/d') ?? '日付不明';
        });
        // 表示モードに応じて、最新1日分 or 全件
        $groupedAll = $isAllView ? $groupedAllAll : $groupedAllAll->slice(0, 1);

        // 自分の記録（ログイン時のみ）
        $groupedMine = collect();
        if (!empty($currentUserId)) {
            $myQuery = Post::with('author:id,name')
                ->where('user', $currentUserId)
                ->orderBy('created_at', 'desc');
            if (!empty($currentCategory)) {
                $myQuery->where('category', $currentCategory);
            }
            $myPosts = $myQuery->get();
            $groupedMine = $myPosts->groupBy(function ($post) {
                return optional($post->created_at)->format('Y/m/d') ?? '日付不明';
            });
        }

        return view('index', [
            'groupedAll' => $groupedAll,
            'groupedMine' => $groupedMine,
            'currentCategory' => $currentCategory,
            'isAllView' => $isAllView,
        ]);
    }
}