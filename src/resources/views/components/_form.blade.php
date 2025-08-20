<form
    action="{{ route('posts.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="post-form"
>
    @csrf

    <!-- {{-- 🔽 バリデーションエラー表示（フォーム内の最上部） --}} -->
    @if ($errors->any())
    <div class="post-form__errors">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ✅ エラー時のみ、自動的にモーダルを再表示させるJS -->
    @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // モーダルのIDを取得
            const modal = document.getElementById("modalOverlay");

            // モーダルが存在すれば「hidden」クラスを削除して表示
            if (modal) {
                modal.classList.remove("hidden");
            }
        });
    </script>
    @endif

    <!-- 閉じるボタン（フォーム左上固定） -->
    <button
        type="button"
        id="closeModal"
        class="modal-close"
        aria-label="閉じる"
    >
        ×
    </button>

    <!-- タイトル -->
    <div class="post-form__group">
        <label for="title" class="post-form__label">タイトル</label>
        <input
            type="text"
            name="title"
            id="title"
            class="post-form__input"
            maxlength="30"
            value="{{ old('title') }}"
            placeholder="作品名"
            {{--
            ←
            サンプル文言
            --}}
        />
    </div>

    <!-- カテゴリ -->
    <div class="post-form__group">
        <label for="category" class="post-form__label">カテゴリ</label>
        <select name="category" id="category" class="post-form__select">
            <option value="映画">映画</option>
            <option value="動画">動画</option>
            <option value="ドラマ">ドラマ</option>
            <option value="アニメ">アニメ</option>
            <option value="小説">小説</option>
            <option value="コミック">コミック</option>
            <option value="音楽">音楽</option>
            <option value="ゲーム">ゲーム</option>
        </select>
    </div>

    <!-- アップロード画像選択 -->
    <div class="post-form__group">
        <label for="images" class="post-form__label"
            >画像アップロード（最大3枚）image|max:2048</label
        >
        <p class="post-form__hint">
            画像を２枚以上アップするには、Ctrl+複数選択（または複数タップ）
        </p>
        <input type="file" name="images[]" id="images" multiple />
    </div>

    <!-- ✅ プレビュー画像表示用 -->
    <div class="post-form__preview" id="imagePreviewContainer"></div>

    <!-- 評価 -->
    <div class="post-form__group">
        <label class="post-form__label">評価</label>
        <div class="post-form__stars">
            @for ($i = 1; $i <= 5; $i++) <input type="radio" id="rating{{ $i }}"
            name="rating" value="{{ $i }}"
            {{ old("rating") == $i ? "checked" : "" }}>
            <label for="rating{{ $i }}">★{{ $i }}</label>
            @endfor
        </div>
    </div>

    <!-- タグ -->
    <div class="post-form__group">
        <label for="tags" class="post-form__label">タグ（カンマ区切り）</label>
        <input
            type="text"
            name="tags"
            id="tags"
            class="post-form__input"
            maxlength="50"
            placeholder="例：推理,アクション(未入力可)"
            value="{{ old('tags') }}"
        />
    </div>

    <!-- コメント -->
    <div class="post-form__group">
        <label for="comment" class="post-form__label"
            >レコメンド（最大140文字）</label
        >
        <textarea
            name="comment"
            id="comment"
            maxlength="140"
            rows="3"
            class="post-form__textarea post-form__textarea--comment"
            placeholder="感想を入力（未入力可）"
            >{{ old("comment") }}</textarea
        >
    </div>

    <!-- 送信ボタン -->
    <button type="submit" class="post-form__submit">記録する</button>
</form>

@section('js')
<script>
    document.getElementById("images").addEventListener("change", function (e) {
        const maxImages = 3;
        const maxSize = 2048;
        const previewContainer = document.getElementById(
            "imagePreviewContainer"
        );
        const files = Array.from(e.target.files);

        previewContainer.innerHTML = "";

        if (files.length > maxImages) {
            alert(`画像は最大 ${maxImages} 枚までアップロードできます。`);
            this.value = "";
            return;
        }

        files.forEach((file) => {
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

                    thumb
                        .querySelector(".remove-btn")
                        .addEventListener("click", () => {
                            thumb.remove();
                            // remove from input? → JavaScriptで input file の files は直接削除できない
                            alert(
                                "画像を削除しました。フォームを再度選択しなおしてください。"
                            );
                        });

                    previewContainer.appendChild(thumb);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    });

    // ✅ リサイズ関数（アスペクト比維持して長辺を maxLength に収める）
    function resizeImage(img, maxLength) {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        const isLandscape = img.width >= img.height;
        const ratio = isLandscape
            ? maxLength / img.width
            : maxLength / img.height;

        if (ratio >= 1) {
            // リサイズ不要
            canvas.width = img.width;
            canvas.height = img.height;
        } else {
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
        }

        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL("image/jpeg", 0.9);
    }
</script>
@endsection
