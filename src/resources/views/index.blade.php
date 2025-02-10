@extends('layouts.default')

@section('title', 'トップページ')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/index.css')  }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.header')

<div class="border">
    <ul class="border__list">
        <li><a href="{{ route('items.list', ['tab'=>'recommend', 'search'=>$search]) }}">おすすめ</a></li>
        @if(!auth()->guest())<!-- ゲストじゃない場合 -->        
        <li></li>
        @endif
    </ul>
</div>

@endsection