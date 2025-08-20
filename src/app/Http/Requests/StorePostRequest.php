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
            'title' => 'required|string|max:30', // 30文字に拡張
            'category' => 'required|string|max:50',
            'images' => 'nullable|array|max:3',          // 最大3枚まで
            // 画像: JPEG/PNG/WebP のみ、最大2MB
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'rating' => 'required|integer|min:1|max:5',
            'tags' => 'nullable|string|max:50',          // 任意だが制限追加推奨
            'comment' => 'nullable|string|max:140',
        ];
    }

    public function messages(): array
    {
        return [

            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは最大30文字までです。',
            'category.required' => 'カテゴリは必須です。',
            'category.string' => 'カテゴリは文字列で入力してください。',
            'category.max' => 'カテゴリは最大50文字までです。',

            'images.array' => '画像は配列で指定してください。',
            'images.max' => '画像は最大3枚までアップロードできます。',
            'images.*.image' => '各ファイルは画像ファイルである必要があります。',
            'images.*.mimes' => '画像は jpg、jpeg、png、webp のいずれかを指定してください。',
            'images.*.max' => '各画像は2MB以内である必要があります。',

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
