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
                        <div class="card__meta">
                            <span class="card__rating"
                                >★ {{ $post->rating ?? '-' }}</span
                            >
                            @if(isset($post->author))
                            <span
                                class="card__author"
                                >{{ $post->author->name }}</span
                            >
                            @endif @if(!empty($post->category))
                            <span
                                class="card__category"
                                >{{ $post->category }}</span
                            >
                            @endif
                            <span
                                class="card__date"
                                >{{ optional($post->created_at)->format('Y/m/d') }}</span
                            >
                        </div>
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
                        <div class="card__meta">
                            <span class="card__rating"
                                >★ {{ $post->rating ?? '-' }}</span
                            >
                            @if(isset($post->author))
                            <span
                                class="card__author"
                                >{{ $post->author->name }}</span
                            >
                            @endif @if(!empty($post->category))
                            <span
                                class="card__category"
                                >{{ $post->category }}</span
                            >
                            @endif
                            <span
                                class="card__date"
                                >{{ optional($post->created_at)->format('Y/m/d') }}</span
                            >
                        </div>
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
