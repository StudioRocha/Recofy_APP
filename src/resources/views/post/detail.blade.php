@extends('layouts.app') @section('css')
<link rel="stylesheet" href="{{ asset('css/post/post-detail.css') }}" />
@endsection @section('content')
<div class="post-detail">
    <div class="post-detail__hero">
        <div class="post-detail__back">
            <form onsubmit="handleBack(event)">
                <button type="submit" class="post-detail__back-btn" aria-label="戻る">
                    <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor" d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
            </form>
        </div>
        @php $paths = is_array($post->image_path) ? $post->image_path :
        (json_decode($post->image_path, true) ?? []); $first = $paths[0] ??
        null; if ($first) { $first = preg_replace('#^(public/|storage/)#', '',
        $first); } @endphp @if($first)
        <img
            src="{{ Storage::url($first) }}"
            alt="{{ $post->title }}"
            class="post-detail__hero-img"
        />
        @else
        <div class="post-detail__noimage">No Image</div>
        @endif @php $ratingValue = (int) ($post->rating ?? 0); $ratingValue =
        max(0, min(5, $ratingValue)); $stars = str_repeat('★', $ratingValue) .
        str_repeat('☆', 5 - $ratingValue); @endphp
        <div class="rating-badge" aria-label="評価">
            {{ $stars }}
        </div>
        <div class="post-detail__like">
            <button type="button" class="like-btn" data-post-id="{{ $post->id }}" aria-label="いいね">❤️</button>
            <span class="like-count" data-post-id="{{ $post->id }}">{{ $post->liked_by_users_count ?? $post->likedByUsers()->count() }}</span>
        </div>
    </div>

    <div class="post-detail__body">
        <h1 style="margin: 0 0 8px 0; font-size: 1.2rem">{{ $post->title }}</h1>
        <div class="post-detail__meta">
            <span>投稿者: {{ optional($post->author)->name ?? '不明' }}</span>
            <span>評価: ★ {{ $post->rating ?? '-' }}</span>
            @if($post->category)
            <span>カテゴリ: {{ $post->category }}</span>
            @endif
            <span
                >投稿日:
                {{ optional($post->created_at)->format('Y/m/d H:i') }}</span
            >
            <span
                >最終更新:
                {{ optional($post->updated_at)->format('Y/m/d H:i') }}</span
            >
        </div>

        @if(!empty($post->comment)) @php $isOwner = auth()->check() &&
        auth()->id() === (int) $post->user; @endphp @if($isOwner)
        <div id="postComment" class="comment-box">
            {{ $post->comment }}
        </div>
        @else
        <button id="toggleCommentBtn" type="button" class="btn-comment-toggle">
            感想をチェックする
        </button>
        <div id="postComment" class="hidden comment-box">
            {{ $post->comment }}
        </div>
        @endif @endif @php $rest = array_slice($paths, 1); @endphp
        @if(is_array($paths) && count($rest) > 0)
        <div class="post-detail__thumbs">
            @foreach($rest as $path) @php $p =
            preg_replace('#^(public/|storage/)#', '', $path); @endphp
            <img
                src="{{ Storage::url($p) }}"
                alt="image"
                class="post-detail__thumb-img"
            />
            @endforeach
        </div>
        @endif

   
        @auth @if(auth()->id() === (int) $post->user)
        <div style="margin-top: 16px">
            <a
                href="{{ route('posts.edit', $post->id) }}"
                class="post-form__submit"
                style="
                    display: inline-block;
                    text-decoration: none;
                    text-align: center;
                "
                >編集する</a
            >
            <form
                method="POST"
                action="{{ route('posts.destroy', $post->id) }}"
                style="margin-top: 10px"
            >
                @csrf @method('DELETE')
                <button type="submit" class="post-form__cancel">
                    記録を削除
                </button>
            </form>
        </div>
        @endif @endauth
    </div>
    </div>
</div>
@endsection @section('js')
<script>
    // Like toggle（詳細）
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.like-btn');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        const postId = btn.getAttribute('data-post-id');
        try {
            const res = await fetch(`{{ url('/posts') }}/${postId}/like/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) return;
            const json = await res.json();
            const countEl = document.querySelector(`.like-count[data-post-id="${postId}"]`);
            if (countEl && typeof json.count === 'number') {
                countEl.textContent = json.count;
            }
        } catch(err) {
            console.error(err);
        }
    });

    (function () {
        const btn = document.getElementById("toggleCommentBtn");
        const comment = document.getElementById("postComment");
        if (!btn || !comment) return;
        btn.addEventListener("click", function () {
            const willShow = comment.classList.contains("hidden");
            comment.classList.toggle("hidden");
            btn.textContent = willShow ? "感想を隠す" : "感想をチェックする";
        });
    })();

    function handleBack(e) {
        e.preventDefault();
        const fromIndex = document.getElementById('fromIndex');
        if (fromIndex && fromIndex.value === '1' && window.history.length > 1) {
            window.history.back();
            return false;
        }
        window.location.href = "{{ route('home') }}";
        return false;
    }
</script>
@endsection
