@extends('layouts.app') @section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />

@endsection @section('content')
<div class="index-page">
    <h2 class="index-page__title">最近の記録</h2>
    @forelse($groupedPosts as $date => $posts)
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
    @endforelse
</div>
@endsection
