@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/post-form.css') }}">
@endsection



@section('content')
<div class="index-page">
    <h2 class="index-page__title">感動を「記録」して共有・振り返るSNS風アプリ</h2>

    <!-- モーダル本体表示 -->
    <div id="modalOverlay" class="modal-overlay hidden">
        <div class="modal-content">
            {{-- フォーム（閉じるボタン含む） --}}
            @include('components._form')
        </div>

        <!-- ✅ 閉じるボタン：フォームの外、モーダルの一番下に配置 -->
        <div class="modal__footer">
            <button type="button" id="closeModal" class="modal-close">❌ 閉じる</button>
        </div>

    </div>


</div>
@endsection