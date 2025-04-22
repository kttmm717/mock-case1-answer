@extends('layouts.default')

@section('title', '取引ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/deal.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="wrapper">
    <main>
        <div class="top fixed">
            <div class="top__partner--info">
                <img src="{{asset('img/icon.png')}}" alt="">
                <h1>{{$partner->name}}さんとの取引画面</h1>
            </div>
            <div>
                <a href="">取引を完了する</a>
            </div>
        </div>

        <div class="item__info fixed">
            <div>
                <img src="{{\Storage::url($item->img_url)}}" alt="商品画像">
            </div>
            <div class="item__text">
                <p class="item__name">{{$item->name}}</p>
                <p class="item__price">&yen; {{$formattedPrice}}</p>
            </div>
        </div>

        <div class="message">
            @foreach($messages as $message)
            @if($message->myself_id !== $user->id)
            <div class="partner">
                <div class="user__info">
                    <img src="{{asset('img/icon.png')}}">
                    <span>{{$message->myself->name}}</span>
                </div>
                <div>
                    <p>{{$message->message}}</p>
                </div>
                @if(!empty($message->img_url))
                <div class="send__img">
                    <img src="{{\Storage::url($message->img_url)}}">
                </div>
                @endif
            </div>
            @else
            <div class="myself">
                <div class="user__info">
                    <img src="{{asset('img/icon.png')}}">
                    <span>{{$message->myself->name}}</span>
                </div>
                <div>
                    <p>{{$message->message}}</p>
                </div>
                @if(!empty($message->img_url))
                <div class="send__img">
                    <img src="{{\Storage::url($message->img_url)}}">
                </div>
                @endif
                <div>
                    <a class="edit" href="">編集</a>
                    <a class="delete" href="">削除</a>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <div class="send fixed">
            @error('message')
            <p class="error">{{$message}}</p>
            @enderror
            @error('img_url')
            <p class="error">{{$message}}</p>
            @enderror
            <form action="/send/{{$item->id}}/{{$user->id}}/{{$partner->id}}" method="post" enctype="multipart/form-data">
                @csrf
                <input class="send__message" type="text" name="message" placeholder="取引メッセージを記入してください">
                <label>
                    <input class="input__label" name="img_url" type="file" accept="image/png, image/jpeg">
                    画像を追加
                </label>
                <button type="submit">
                    <img src="{{\Storage::url('img/send.jpg')}}" alt="送信">
                </button>
            </form>
        </div>
    </main>

    <aside fixed>
        <h2>その他の取引</h2>
        <a href="">商品名</a>
        <a href="">商品名</a>
        <a href="">商品名</a>
    </aside>
</div>
@endsection