<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:20', // ←追加
            'category' => 'required|string',
            'images' => 'nullable|array|max:4',          // 最大4枚まで
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',

            'rating' => 'required|integer|min:1|max:5',
            'tags' => 'nullable|string|max:50',          // 任意だが制限追加推奨
            'comment' => 'nullable|string|max:140',
        ];
    }

    public function messages(): array
    {
        return [

            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは最大20文字までです。',
            'category.required' => 'カテゴリは必須です。',
            'category.string' => 'カテゴリは文字列で入力してください。',

            'images.array' => '画像は配列で指定してください。',
            'images.max' => '画像は最大4枚までアップロードできます。',
            'images.*.image' => '各ファイルは画像ファイルである必要があります。',
            'images.*.max' => '各画像は1MB以内である必要があります。',

            'rating.required' => '評価は必須です。',
            'rating.integer' => '評価は整数で入力してください。',
            'rating.min' => '評価は最低 :min です。',
            'rating.max' => '評価は最大 :max です。',

            'tags.string' => 'タグは文字列で入力してください。',
            'tags.max' => 'タグは :max 文字以内で入力してください。',

            'comment.string' => '感想は文字列で入力してください。',
            'comment.max' => '感想は :max 文字以内で入力してください。',

        ];
    }
}
