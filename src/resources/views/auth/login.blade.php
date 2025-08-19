@extends('layouts.app') @section('content')
<div class="post-form" style="max-width: 420px; margin: 80px auto">
    <h2 style="text-align: center; margin-bottom: 12px">ログイン</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf @if ($errors->login->any())
        <div class="post-form__errors">
            <ul>
                @foreach ($errors->login->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="post-form__group">
            <label class="post-form__label">メールアドレス</label>
            <input
                class="post-form__input"
                type="email"
                name="email"
                required
                autofocus
                value="{{ old('email') }}"
            />
        </div>
        <div class="post-form__group">
            <div style="display: flex; gap: 8px; align-items: center">
                <label class="post-form__label" for="login_password"
                    >パスワード</label
                >
                <button
                    type="button"
                    id="toggleLoginPassword"
                    aria-label="パスワードを表示"
                    class="password-toggle-btn"
                >
                    🙈
                </button>
            </div>
            <input
                id="login_password"
                class="post-form__input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                minlength="8"
            />
        </div>
        <div class="post-form__group">
            <label
                ><input type="checkbox" name="remember"
                {{ old("remember") ? "checked" : "" }} />
                ログイン状態を保持</label
            >
        </div>
        <button class="post-form__submit" type="submit">ログイン</button>
    </form>
    <div style="text-align: center; margin-top: 12px">
        <a
            href="{{ route('register') }}"
            style="color: #1ac9a0; text-decoration: underline"
            >新規登録はこちら</a
        >
    </div>
</div>
@endsection @section('js')
<script>
    (function () {
        const input = document.getElementById("login_password");
        const btn = document.getElementById("toggleLoginPassword");
        if (input && btn) {
            btn.addEventListener("click", function () {
                const isPwd = input.getAttribute("type") === "password";
                input.setAttribute("type", isPwd ? "text" : "password");
                btn.textContent = isPwd ? "👁" : "🙈";
                btn.setAttribute(
                    "aria-label",
                    isPwd ? "パスワードを非表示" : "パスワードを表示"
                );
            });
        }
    })();
</script>
@endsection
