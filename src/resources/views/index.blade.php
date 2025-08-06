@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/post-form.css') }}">


@endsection

@section('content')
<div class="index-page">
    <h2 class="index-page__title">感動を「記録」して共有・振り返りできるSNS風アプリ</h2>

    <!-- 投稿フォームの読み込み -->
    @include('components._form')

</div>
@endsection