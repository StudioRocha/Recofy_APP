<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
    class="post-form">
    @csrf

    <!-- カテゴリ -->
    <div class="post-form__group">
        <label for="category" class="post-form__label">カテゴリ</label>
        <select name="category" id="category" class="post-form__select">
            <option value="movie">映画</option>
            <option value="book">本</option>
            <option value="music">音楽</option>
            <option value="travel">旅行</option>
        </select>
    </div>

    <!-- 画像 -->
    <div class="post-form__group">
        <label for="image" class="post-form__label">画像アップロード</label>
        <input type="file" name="image" id="image" class="post-form__file">
    </div>

    <!-- 評価 -->
    <div class="post-form__group">
        <label class="post-form__label">評価</label>
        <div class="post-form__stars">
            @for ($i = 1; $i <= 5; $i++)
                <label><input type="radio" name="rating" value="{{ $i }}"> ★{{ $i }}</label>
                @endfor
        </div>
    </div>

    <!-- タグ -->
    <div class="post-form__group">
        <label for="tags" class="post-form__label">タグ（カンマ区切り）</label>
        <input type="text" name="tags" id="tags" class="post-form__input">
    </div>

    <!-- コメント -->
    <div class="post-form__group">
        <label for="comment" class="post-form__label">感想（最大140文字）</label>
        <textarea
            name="comment"
            id="comment"
            maxlength="140"
            rows="3"
            class="post-form__textarea post-form__textarea--comment">
        </textarea>
    </div>

    <!-- 送信ボタン -->
    <button type="submit" class="post-form__submit">記録する</button>
</form>