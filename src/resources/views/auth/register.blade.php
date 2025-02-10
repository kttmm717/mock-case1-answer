@extends('layouts.default')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/authentication.css')  }}">
@endsection

@section('content')

@include('components.header')
<form action="/register" method="post" class="authenticate center">
    @csrf
    <h1 class="page__title">会員登録</h1>

    <label for="name" class="entry__name">ユーザー名</label>
    <input type="text" id="name" name="name" class="input" value="{{old('name')}}">
    <div class="form__error">
        @error('name')
        {{$message}}
        @enderror
    </div>

    <label for="email" class="entry__name">メールアドレス</label>
    <input type="text" id="email" name="email" class="input" value="{{old('email')}}">
    <div class="form__error">
        @error('email')
        {{$message}}
        @enderror
    </div>

    <label for="password" class="entry__name">パスワード</label>
    <input type="password" id="password" name="password" class="input">
    <div class="form__error">
        @error('password')
        {{$message}}
        @enderror
    </div>

    <label for="password_confirm" class="entry__name">確認用パスワード</label>
    <input type="password" id="password_confirm" name="password_confirmation" class="input">
    
    <button class="btn btn--big">登録する</button>
    <a href="/login" class="link">ログインはこちら</a>
</form>

@endsection