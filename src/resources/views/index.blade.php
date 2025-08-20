@extends('layouts.app') @section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />

@endsection @section('content')
<div class="index-page">
    <h1
        class="index-page__title"
        style="display: flex; align-items: center; gap: 12px"
    >
        <span>みんなの記録</span>
        @if(!$isAllView)
        <a
            href="{{ route('home', array_filter(['category' => $currentCategory, 'view' => 'all'])) }}"
            class="index-page__more"
            >もっと見る</a
        >
        @else
        <a
            href="{{ route('home', array_filter(['category' => $currentCategory])) }}"
            class="index-page__more"
            >戻る</a
        >
        @endif
    </h1>
    @forelse($groupedAll as $date => $posts)
    <section class="cards-section">
        <h3 class="cards-section__date">{{ $date }}</h3>
        <div class="cards">
            @foreach($posts as $post)
            <article class="card">
                <a
                    href="{{ route('posts.show', $post->id) }}"
                    class="card__link"
                >
                    <div class="card__header">
                        <h3 class="card__title">{{ $post->title }}</h3>
                    </div>

                    <div class="card__thumb">
                        @php $paths = is_array($post->image_path) ?
                        $post->image_path : (json_decode($post->image_path,
                        true) ?? []); $first = $paths[0] ?? null; if ($first) {
                        $first = preg_replace('#^(public/|storage/)#', '',
                        $first); } @endphp @if($first)
                        <img
                            src="{{ Storage::url($first) }}"
                            alt="{{ $post->title }}"
                        />
                        @else

                        <div class="card__placeholder">No Image</div>

                        @endif

                        <div class="card__overlay-meta" aria-label="投稿情報">
                            <ul class="overlay-list">
                                @if(isset($post->author))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->author->name }}</span
                                    >
                                </li>
                                @endif @if(!empty($post->category))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->category }}</span
                                    >
                                </li>
                                @endif @if(!empty($post->tags))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->tags }}</span
                                    >
                                </li>
                                @endif
                            </ul>
                        </div>

                        <div class="card__like">
                            <button
                                type="button"
                                class="like-btn {{ ($post->liked_by_me ?? 0) > 0 ? 'is-liked' : '' }}"
                                data-post-id="{{ $post->id }}"
                                aria-label="いいね"
                            >
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.53C11.09 5.01 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                    />
                                </svg>
                            </button>
                            <span
                                class="like-count"
                                data-post-id="{{ $post->id }}"
                                >{{ $post->liked_by_users_count ?? 0 }}</span
                            >
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </section>
    @empty
    <p class="cards__empty">投稿がありません</p>
    @endforelse @auth
    <h2 class="index-page__title" style="margin-top: 24px">あなたの記録</h2>
    @forelse($groupedMine as $date => $posts)
    <section class="cards-section">
        <h3 class="cards-section__date">{{ $date }}</h3>
        <div class="cards">
            @foreach($posts as $post)
            <article class="card">
                <a
                    href="{{ route('posts.show', $post->id) }}"
                    class="card__link"
                >
                    <div class="card__header">
                        <h3 class="card__title">{{ $post->title }}</h3>
                    </div>
                    <div class="card__thumb">
                        @php $paths = is_array($post->image_path) ?
                        $post->image_path : (json_decode($post->image_path,
                        true) ?? []); $first = $paths[0] ?? null; if ($first) {
                        $first = preg_replace('#^(public/|storage/)#', '',
                        $first); } @endphp @if($first)
                        <img
                            src="{{ Storage::url($first) }}"
                            alt="{{ $post->title }}"
                        />
                        @else
                        <div class="card__placeholder">No Image</div>
                        @endif

                        <div class="card__overlay-meta" aria-label="投稿情報">
                            <ul class="overlay-list">
                                @if(isset($post->author))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->author->name }}</span
                                    >
                                </li>
                                @endif @if(!empty($post->category))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->category }}</span
                                    >
                                </li>
                                @endif @if(!empty($post->tags))
                                <li class="overlay-item">
                                    <span
                                        class="overlay-value"
                                        >{{ $post->tags }}</span
                                    >
                                </li>
                                @endif
                            </ul>
                        </div>

                        <div class="card__like">
                            <button
                                type="button"
                                class="like-btn"
                                data-post-id="{{ $post->id }}"
                                aria-label="いいね"
                            >
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.53C11.09 5.01 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                    />
                                </svg>
                            </button>
                            <span
                                class="like-count"
                                data-post-id="{{ $post->id }}"
                                >{{ $post->liked_by_users_count ?? 0 }}</span
                            >
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </section>
    @empty
    <p class="cards__empty">あなたの投稿はありません</p>
    @endforelse @endauth
</div>
@endsection @section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Like toggle (一覧)
        document.addEventListener("click", async function (e) {
            const btn = e.target.closest(".like-btn");
            if (!btn || btn.classList.contains("like-btn--disabled")) return;
            // いいねボタン押下時はカードリンク遷移を抑止
            e.preventDefault();
            e.stopPropagation();
            const postId = btn.getAttribute("data-post-id");
            try {
                const res = await fetch(
                    `${"{{ url('/posts') }}"}/${postId}/like/toggle`,
                    {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            Accept: "application/json",
                        },
                    }
                );
                if (!res.ok) return;
                const json = await res.json();
                const countEl = document.querySelector(
                    `.like-count[data-post-id="${postId}"]`
                );
                if (countEl && typeof json.count === "number") {
                    countEl.textContent = json.count;
                }
                // sparkle effect (増加時のみ)
                if (json.liked) {
                    btn.classList.remove("sparkle");
                    void btn.offsetWidth; // reflow to restart animation
                    btn.classList.add("sparkle");
                }
                // 色のトグル
                btn.classList.toggle("is-liked", !!json.liked);
            } catch (err) {
                console.error(err);
            }
        });
        // 戻ってきたときにスクロール位置を復元
        const saved = sessionStorage.getItem("indexScroll");
        if (saved !== null) {
            const y = parseInt(saved, 10);
            if (!Number.isNaN(y)) {
                window.scrollTo({ top: y, left: 0, behavior: "auto" });
            }
            sessionStorage.removeItem("indexScroll");
        }

        // 詳細へ遷移する前に現在位置を保存
        document.querySelectorAll(".card__link").forEach(function (a) {
            a.addEventListener(
                "click",
                function () {
                    sessionStorage.setItem(
                        "indexScroll",
                        String(window.scrollY)
                    );
                },
                { passive: true }
            );
        });
    });
</script>
@endsection
