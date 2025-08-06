<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recofy</title>

    <!-- リセットCSS（sanitize） -->
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">

    <!-- 共通CSS（パーツデザインなど） -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">


    <!-- 各ページごとのCSS -->
    @yield('css')
</head>

<body class="layout">

    <!-- 🔼 ヘッダー -->
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">Recofy</h1>
            <div class="header__search">🔍</div>
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
        @yield('content')

        <!-- ✅ 共通モーダル（フォーム用） -->
        <div id="modalOverlay" class="modal-overlay hidden">
            <div class="modal-content">
                {{-- 投稿フォームの読み込み --}}
                @include('components._form')

                {{-- 閉じるボタン（フォームの外・一番下） --}}
                <div class="modal__footer">
                    <button type="button" id="closeModal" class="modal-close">❌ 閉じる</button>
                </div>
            </div>
        </div>
    </main>

    <!-- ナビゲーションバーのすぐ下などに配置 -->
    @include('components.modal_form')

    <!-- フッター直前にJS -->
    <script src="{{ asset('js/modal-form.js') }}"></script>

    <!-- 🔽 ナビゲーションバー -->
    <nav class="nav-bottom">
        <ul class="nav-bottom__menu">
            <li>
                <a href="{{ route('home') }}" class="nav-item">
                    <div class="nav-icon">🏠</div>
                    <div class="nav-label">ホーム</div>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <div class="nav-icon">🔔</div>
                    <div class="nav-label">通知</div>
                </a>
            </li>
            <li>
                <button id="openModal" type="button" class="nav-item nav-item--reco">
                    <div class="nav-icon">➕</div>
                    <div class="nav-label">Reco!</div>
                </button>
            </li>
            <li>
                <a href="{{ route('posts.index') }}" class="nav-item">
                    <div class="nav-icon">📋</div>
                    <div class="nav-label">一覧</div>
                </a>
            </li>
            <li>
                <a href="{{ route('mypage') }}" class="nav-item">
                    <div class="nav-icon">👤</div>
                    <div class="nav-label">マイページ</div>
                </a>
            </li>
        </ul>
    </nav>


    <!-- 各ページで定義されたJSの読み込み -->
    @yield('js')



</body>

</html>