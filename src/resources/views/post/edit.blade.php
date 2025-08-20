@extends('layouts.app') @section('content')
<div class="post-form" style="max-width: 520px; margin: 80px auto">
    <h2 style="text-align: center">記録の編集</h2>

    @if ($errors->any())
    <div class="post-form__errors">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form
        method="POST"
        action="{{ route('posts.update', $post->id) }}"
        enctype="multipart/form-data"
    >
        @csrf @method('PUT')

        <!-- タイトル（変更可） -->
        <div class="post-form__group">
            <label class="post-form__label" for="title">タイトル</label>
            <input
                id="title"
                name="title"
                class="post-form__input"
                type="text"
                maxlength="30"
                value="{{ old('title', $post->title) }}"
            />
        </div>

        <!-- カテゴリ -->
        <div class="post-form__group">
            <label class="post-form__label" for="category">カテゴリ</label>
            <select name="category" id="category" class="post-form__select">
                @php $cat = old('category', $post->category); @endphp
                <option value="映画" {{ $cat==='映画' ? 'selected' : '' }}>映画</option>
                <option value="動画" {{ $cat==='動画' ? 'selected' : '' }}>動画</option>
                <option value="ドラマ" {{ $cat==='ドラマ' ? 'selected' : '' }}>ドラマ</option>
                <option value="アニメ" {{ $cat==='アニメ' ? 'selected' : '' }}>アニメ</option>
                <option value="小説" {{ $cat==='小説' ? 'selected' : '' }}>小説</option>
                <option value="コミック" {{ $cat==='コミック' ? 'selected' : '' }}>コミック</option>
                <option value="音楽" {{ $cat==='音楽' ? 'selected' : '' }}>音楽</option>
                <option value="ゲーム" {{ $cat==='ゲーム' ? 'selected' : '' }}>ゲーム</option>
            </select>
        </div>

        <!-- 画像（再アップで差し替え） -->
        <div class="post-form__group">
            <label class="post-form__label" for="images"
                >画像アップロード（最大3枚）image|max:2048</label
            >
            <p class="post-form__hint">
                画像を２枚以上アップするには、Ctrl+複数選択（または複数タップ）
            </p>
            <input type="file" name="images[]" id="images" multiple />
        </div>
        <div class="post-form__preview" id="imagePreviewContainer"></div>

        <!-- トップ画像は下の既存画像一覧から選択してください -->

        <!-- 現在の画像一覧（✖ボタンで削除指定＆個別をトップに指定可） -->
        @php 
            $existing = is_array($post->image_path) ? $post->image_path : (json_decode($post->image_path, true) ?? []);
            $selectedCover = old('cover', $existing[0] ?? null);
        @endphp
        @if(count($existing) > 0)
        <div class="post-form__group">
            <label class="post-form__label">現在の画像</label>
            <div class="post-form__preview" id="existingImagesContainer">
                @foreach($existing as $path)
                @php $p = preg_replace('#^(public/|storage/)#', '', $path); @endphp
                <div style="display:flex; flex-direction:column; align-items:center; gap:6px;">
                    <div class="thumb" style="position: relative;">
                        <img src="{{ Storage::url($p) }}" alt="current" />
                        <button
                            type="button"
                            class="remove-btn remove-existing-btn"
                            data-image-path="{{ $path }}"
                            aria-label="画像を削除"
                        >✖</button>
                    </div>
                    <label style="font-size:0.8rem; text-align:center;">
                        <input type="radio" name="cover" value="{{ $path }}" {{ $selectedCover === $path ? 'checked' : '' }}>
                        これをトップに
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 評価（星の段階評価） -->
        <div class="post-form__group">
            <label class="post-form__label">評価</label>
            <div class="post-form__stars">
                @php $current = (int) old('rating', $post->rating); @endphp
                @for ($i = 1; $i <= 5; $i++)
                <input
                    type="radio"
                    id="rating{{ $i }}"
                    name="rating"
                    value="{{ $i }}"
                    {{ $current === $i ? 'checked' : '' }}
                />
                <label for="rating{{ $i }}">★{{ $i }}</label>
                @endfor
            </div>
        </div>

        <!-- タグ -->
        <div class="post-form__group">
            <label class="post-form__label" for="tags">タグ（カンマ区切り）</label>
            <input
                type="text"
                name="tags"
                id="tags"
                class="post-form__input"
                maxlength="50"
                placeholder="例：推理,アクション(未入力可)"
                value="{{ old('tags', $post->tags) }}"
            />
        </div>

        <!-- 感想 -->
        <div class="post-form__group">
            <label class="post-form__label" for="comment">感想</label>
            <textarea
                class="post-form__textarea"
                id="comment"
                name="comment"
                rows="6"
            >{{ old('comment', $post->comment) }}</textarea>
        </div>

        <button class="post-form__submit" type="submit">更新する</button>
        <button type="button" class="post-form__cancel" onclick="history.back()">キャンセル</button>
    </form>
</div>
@endsection

@section('js')
<script>
    document.getElementById("images").addEventListener("change", function (e) {
        const maxImages = 3;
        const maxSize = 2048;
        const previewContainer = document.getElementById("imagePreviewContainer");
        const files = Array.from(e.target.files);

        previewContainer.innerHTML = "";

        if (files.length > maxImages) {
            alert(`画像は最大 ${maxImages} 枚までアップロードできます。`);
            this.value = "";
            return;
        }

        files.forEach((file, idx) => {
            if (!file.type.startsWith("image/")) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    const resizedDataUrl = resizeImage(img, maxSize);
                    const thumb = document.createElement("div");
                    thumb.className = "thumb";
                    thumb.style.position = "relative";
                    thumb.style.display = "inline-block";
                    thumb.style.margin = "5px";
                    thumb.innerHTML = `
                        <img src="${resizedDataUrl}" width="150" height="150" style="object-fit: cover;">
                        <label style="display:block; text-align:center; font-size:0.8rem; margin-top:4px;">
                            <input type="radio" name="cover" value="new:${idx}"> これをトップに
                        </label>
                        <button type="button" class="remove-btn" style="
                            position: absolute;
                            top: 0;
                            right: 0;
                            background: rgba(0,0,0,0.5);
                            color: white;
                            border: none;
                            border-radius: 50%;
                            width: 24px;
                            height: 24px;
                            cursor: pointer;">✖</button>
                    `;
                    thumb.querySelector(".remove-btn").addEventListener("click", () => {
                        thumb.remove();
                        alert("画像を削除しました。フォームを再度選択しなおしてください。");
                    });
                    previewContainer.appendChild(thumb);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    });

    function resizeImage(img, maxLength) {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const isLandscape = img.width >= img.height;
        const ratio = isLandscape ? maxLength / img.width : maxLength / img.height;
        if (ratio >= 1) {
            canvas.width = img.width;
            canvas.height = img.height;
        } else {
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
        }
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL("image/jpeg", 0.9);
    }

    // 既存画像の✖ボタンで削除指定
    document.querySelectorAll('.remove-existing-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
            const path = this.getAttribute('data-image-path');
            const container = this.closest('.thumb');
            const form = this.closest('form');
            if (!path || !container || !form) return;
            // 削除指定のhidden inputをこの編集フォームに追加
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = path;
            form.appendChild(input);
            // 視覚的に削除
            container.remove();
        });
    });
</script>
@endsection
