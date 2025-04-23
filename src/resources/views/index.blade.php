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
        <li><a class="list {{request('tab') === 'recommend' ? 'active' : ''}}" href="{{ route('items.list', ['tab'=>'recommend', 'search'=>$search]) }}">おすすめ</a></li>
        @if(!auth()->guest())<!-- ゲストじゃない場合マイリスト表示 -->
        <li class="list {{request('tab') === 'mylist' ? 'active' : ''}}"><a href="{{ route('items.list', ['tab'=>'mylist', 'search'=>$search]) }}">マイリスト</a></li>
        @endif
    </ul>
</div>
<div class="container">
    <div class="items">
        @foreach($items as $item)
        <div class="item">
            <a href="/item/{{$item->id}}">
                @if($item->sold())
                <div class="item__img--container sold">
                    <img class="item__img" src="{{ \Storage::url($item->img_url) }}" alt="商品画像">
                </div>
                @else
                <div class="item__img--container">
                    <img class="item__img" src="{{ \Storage::url($item->img_url) }}" alt="商品画像">
                </div>
                @endif
            </a>
            <p class="item__name">{{$item->name}}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection