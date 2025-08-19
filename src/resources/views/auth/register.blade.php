@extends('layouts.app') @section('content')
<div class="post-form" style="max-width: 420px; margin: 80px auto">
    <h2 style="text-align: center; margin-bottom: 12px">新規登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf @if ($errors->register->any())
        <div class="post-form__errors">
            <ul>
                @foreach ($errors->register->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="post-form__group">
            <label class="post-form__label">ユーザー名</label>
            <input
                class="post-form__input"
                type="text"
                name="name"
                required
                autofocus
                value="{{ old('name') }}"
                maxlength="255"
            />
        </div>
        <div class="post-form__group">
            <label class="post-form__label">メールアドレス</label>
            <input
                class="post-form__input"
                type="email"
                name="email"
                required
                value="{{ old('email') }}"
            />
        </div>
        <div class="post-form__group">
            <div style="display: flex; gap: 8px; align-items: center">
                <label class="post-form__label" for="reg_password"
                    >パスワード</label
                >
                <button
                    type="button"
                    id="toggleRegPassword"
                    aria-label="パスワードを表示"
                    class="password-toggle-btn"
                >
                    🙈
                </button>
            </div>
            <input
                id="reg_password"
                class="post-form__input"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                minlength="8"
            />
        </div>
        <div class="post-form__group">
            <div style="display: flex; gap: 8px; align-items: center">
                <label class="post-form__label" for="reg_password_confirm"
                    >パスワード（確認）</label
                >
                <button
                    type="button"
                    id="toggleRegPasswordConfirm"
                    aria-label="パスワードを表示"
                    class="password-toggle-btn"
                >
                    🙈
                </button>
            </div>
            <input
                id="reg_password_confirm"
                class="post-form__input"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                minlength="8"
            />
        </div>
        <button class="post-form__submit" type="submit">登録する</button>
    </form>
    <div style="text-align: center; margin-top: 12px">
        <a
            href="{{ route('login') }}"
            style="color: #1ac9a0; text-decoration: underline"
            >ログインはこちら</a
        >
    </div>
</div>
@endsection @section('js')
<script>
    (function () {
        const toggle = (inputId, btnId) => {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);
            if (!input || !btn) return;
            btn.addEventListener("click", function () {
                const isPwd = input.getAttribute("type") === "password";
                input.setAttribute("type", isPwd ? "text" : "password");
                btn.textContent = isPwd ? "👁" : "🙈";
                btn.setAttribute(
                    "aria-label",
                    isPwd ? "パスワードを非表示" : "パスワードを表示"
                );
            });
        };
        toggle("reg_password", "toggleRegPassword");
        toggle("reg_password_confirm", "toggleRegPasswordConfirm");
    })();
</script>
@endsection
