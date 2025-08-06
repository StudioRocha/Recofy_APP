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

    <!-- 🔽 メイン -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- 🔽 ナビゲーションバー -->
    <nav class="nav-bottom">
        <ul class="nav-bottom__menu">
            <li>
                <a href="{{ route('home') }}">
                    🏠
                    <span class="nav-label">ホーム</span>
                </a>
            </li>
            <li>
                <a href="#">
                    🔔
                    <span class="nav-label">通知</span>
                </a>
            </li>
            <li>
                <a href="{{ route('posts.create') }}">
                    ➕
                    <span class="nav-label">Reco!</span>
                </a>
            </li>
            <li>
                <a href="{{ route('posts.index') }}">
                    📋
                    <span class="nav-label">一覧</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mypage') }}">
                    👤
                    <span class="nav-label">マイページ</span>
                </a>
            </li>
        </ul>
    </nav>


    @yield('js')
</body>

</html>