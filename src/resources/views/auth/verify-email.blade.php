@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/verify.css') }}">
@endsection

@section('content')
@include('components.header')
<div class="mail_notice--div">
    <div class="mail_notice--header">
        <p class="notice_header--p">メール認証はお済みですか？</p>
    </div>

    <div class="mail_notice--content">
        @if(session('resent'))
            <p class="notice_resend--p" role="alert">新規認証メールを送信しました！</p>
        @endif
        <p class="alert_resend--p">
            このページを閲覧するには、Eメールによる認証が必要です。
            もし認証用のメールを受け取っていない場合、
            <form class="mail_resend--form" action="{{ route('verification.send') }}" method="post">
                @csrf
                <button type="submit" class="mail_resend--button">こちらのリンク</button>をクリックしてください。
            </form>
        </p>
    </div>
</div>
@endsection