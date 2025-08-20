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

        <link rel="stylesheet" href="{{ asset('css/post/post-form.css') }}" />

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
                        <div class="user-menu-wrapper">
                            <button
                                type="button"
                                class="header__icon-btn header__icon-btn--user"
                                id="userMenuToggle"
                                aria-label="ユーザーメニュー"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                👤
                            </button>
                            <span
                                class="header__username header__username--button"
                                id="userMenuToggleName"
                                role="button"
                                tabindex="0"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                @auth
                                {{ Auth::user()->name }}
                                @else ゲスト @endauth
                            </span>
                            @auth
                            <div
                                id="userMenu"
                                class="user-menu hidden"
                                role="menu"
                                aria-labelledby="userMenuToggle"
                            >
                                <ul class="user-menu__list">
                                    <li class="user-menu__item">
                                        <a
                                            href="{{ route('mypage') }}"
                                            class="user-menu__link"
                                            >マイページ</a
                                        >
                                    </li>
                                    <li class="user-menu__item">
                                        <a href="#" class="user-menu__link"
                                            >プロフィール</a
                                        >
                                    </li>
                                    <li class="user-menu__item">
                                        <a href="#" class="user-menu__link"
                                            >フォローリスト</a
                                        >
                                    </li>
                                    <li class="user-menu__item">
                                        <a href="#" class="user-menu__link"
                                            >フォロワー</a
                                        >
                                    </li>
                                    <li class="user-menu__item">
                                        <form
                                            method="POST"
                                            action="{{ route('logout') }}"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="user-menu__link user-menu__logout"
                                            >
                                                ログアウト
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 🔽 カテゴリタブ -->
        <div class="category-tabs">
            <ul class="category-tabs__list">
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home') }}"
                        class="{{ request()->filled('category') ? '' : 'is-active' }}"
                        >総合</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => '映画']) }}"
                        class="{{
                            request('category') === '映画' ? 'is-active' : ''
                        }}"
                        >映画</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => '動画']) }}"
                        class="{{
                            request('category') === '動画' ? 'is-active' : ''
                        }}"
                        >動画</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => 'ドラマ']) }}"
                        class="{{
                            request('category') === 'ドラマ' ? 'is-active' : ''
                        }}"
                        >ドラマ</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => 'アニメ']) }}"
                        class="{{
                            request('category') === 'アニメ' ? 'is-active' : ''
                        }}"
                        >アニメ</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => '小説']) }}"
                        class="{{
                            request('category') === '小説' ? 'is-active' : ''
                        }}"
                        >小説</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => 'コミック']) }}"
                        class="{{
                            request('category') === 'コミック'
                                ? 'is-active'
                                : ''
                        }}"
                        >コミック</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => '音楽']) }}"
                        class="{{
                            request('category') === '音楽' ? 'is-active' : ''
                        }}"
                        >音楽</a
                    >
                </li>
                <li class="category-tabs__item">
                    <a
                        href="{{ route('home', ['category' => 'ゲーム']) }}"
                        class="{{
                            request('category') === 'ゲーム' ? 'is-active' : ''
                        }}"
                        >ゲーム</a
                    >
                </li>
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

        <script>
            (function () {
                const toggleBtn = document.getElementById("userMenuToggle");
                const toggleName =
                    document.getElementById("userMenuToggleName");
                const wrapper = document.querySelector(".user-menu-wrapper");
                const menu = document.getElementById("userMenu");
                if (!toggleBtn || !menu || !wrapper) return;

                function closeMenu() {
                    menu.classList.add("hidden");
                    toggleBtn.setAttribute("aria-expanded", "false");
                    if (toggleName)
                        toggleName.setAttribute("aria-expanded", "false");
                }

                function openMenu() {
                    menu.classList.remove("hidden");
                    toggleBtn.setAttribute("aria-expanded", "true");
                    if (toggleName)
                        toggleName.setAttribute("aria-expanded", "true");
                }

                function onToggleClick(e) {
                    e.stopPropagation();
                    const isHidden = menu.classList.contains("hidden");
                    if (isHidden) openMenu();
                    else closeMenu();
                }
                toggleBtn.addEventListener("click", onToggleClick);
                if (toggleName)
                    toggleName.addEventListener("click", onToggleClick);

                document.addEventListener("click", function (e) {
                    if (!wrapper.contains(e.target)) {
                        closeMenu();
                    }
                });
            })();
        </script>

        <!-- 各ページで定義されたJSの読み込み -->
        @yield('js')
    </body>
</html>
