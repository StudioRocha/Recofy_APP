<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // このFormRequestは静的メソッドで共通ルールを提供するための器。
    public function rules(): array
    {
        return [];
    }

    /** 共通: ログイン用の入力検証ルール */
    public static function loginRules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:19'],
        ];
    }

    /** 共通: 登録用の入力検証ルール */
    public static function registerRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:19', 'confirmed'],
        ];
    }

    /** 日本語のエラーメッセージ（ユーティリティ用） */
    public static function messageMap(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式が正しくありません',
            'email.exists' => 'このメールアドレスのユーザーが見つかりません',
            'email.unique' => 'このメールアドレスは既に登録されています',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは20文字未満で入力してください',
            'password.confirmed' => 'パスワード（確認）が一致しません',
            'name.required' => 'ユーザー名を入力してください',
            'name.min' => 'ユーザー名は2文字以上で入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
        ];
    }
}


