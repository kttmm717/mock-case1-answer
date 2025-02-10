@extends('layouts.default')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/authentication.css')  }}">
@endsection

@section('content')

@include('components.header')
<form action="/login" method="post" class="authenticate center">
    @csrf
    <h1 class="page__title">ログイン</h1>

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

    <button class="btn btn--big">ログインする</button>
    <a href="/register" class="link">会員登録はこちら</a>
</form>

@endsection