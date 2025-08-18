<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Recofy</title>

        <!-- リセットCSS（sanitize） -->
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />

        <!-- 共通CSS（パーツデザインなど） -->
        <link rel="stylesheet" href="{{ asset('css/common.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/post-form.css') }}" />

        <!-- 各ページごとのCSS -->
        @yield('css')
    </head>

    <body class="layout">
        <!-- 🔼 ヘッダー -->
        <header class="header fixed-header">
            <div class="header__inner header__table">
                <div class="header__cell header__left">
                    <h1 class="header__logo">
                        <a href="{{ route('home') }}" class="header__logo-link"
                            >Recofy</a
                        >
                    </h1>
                </div>
                <div class="header__cell header__center">
                    <span class="header__search"></span>
                    <input
                        type="text"
                        class="header__search-input"
                        placeholder="コンテンツを探す"
                    />
                </div>
                <div class="header__cell header__right">
                    <div class="header__actions">
                        <a
                            href="{{ route('mypage') }}"
                            class="header__icon-btn"
                            aria-label="マイページ"
                            >👤</a
                        >
                        <span class="header__username">ユーザー名</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- 🔽 カテゴリタブ -->
        <div class="category-tabs">
            <ul class="category-tabs__list">
                <li class="category-tabs__item"><a href="#">総合</a></li>
                <li class="category-tabs__item"><a href="#">映画</a></li>
                <li class="category-tabs__item"><a href="#">動画</a></li>
                <li class="category-tabs__item"><a href="#">本</a></li>
                <li class="category-tabs__item"><a href="#">音楽</a></li>
                <li class="category-tabs__item"><a href="#">ゲーム</a></li>
            </ul>
        </div>

        <!-- 🔽 メイン -->
        <main class="main-content">
            @if (session('success'))
            <div class="flash flash--success">
                {{ session("success") }}
            </div>
            @endif @yield('content')

            <!-- ✅ 共通モーダル（Reco!投稿フォーム） -->
            <div id="modalOverlay" class="modal-overlay hidden">
                <div class="modal-content">
                    {{-- 投稿フォーム本体 --}}
                    @include('components._form')
                </div>
            </div>
        </main>

        <!-- 🔽 下部ナビゲーションタブ（固定） -->
        <nav class="nav-bottom">
            <ul class="nav-bottom__menu">
                <li>
                    <a href="{{ route('home') }}" class="nav-item">
                        <div class="nav-icon">🏠</div>
                        <div class="nav-label">ホーム</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('favorites') }}" class="nav-item">
                        <div class="nav-icon">❤️</div>
                        <div class="nav-label">いいね</div>
                    </a>
                </li>
                <li>
                    <button
                        type="button"
                        class="nav-item nav-item--reco"
                        data-open-modal="true"
                    >
                        <div class="nav-icon">➕</div>
                        <div class="nav-label">記録</div>
                    </button>
                </li>
                <li>
                    <a href="{{ route('records') }}" class="nav-item">
                        <div class="nav-icon">📝</div>
                        <div class="nav-label">一覧</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications') }}" class="nav-item">
                        <div class="nav-icon">🔔</div>
                        <div class="nav-label">通知</div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- フッター直前にJS -->
        <script src="{{ asset('js/modal-form.js') }}"></script>

        <!-- 各ページで定義されたJSの読み込み -->
        @yield('js')
    </body>
</html>
