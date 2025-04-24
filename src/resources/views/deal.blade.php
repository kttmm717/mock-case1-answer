@extends('layouts.default')

@section('title', '取引ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/deal.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="wrapper">
    <main class="main">
        <!-- トップ -->
        <div class="top fixed">
            <div class="top__partner--info">
                <img src="{{asset('img/icon.png')}}" alt="">
                <h1>{{$partner->name}}さんとの取引画面</h1>
            </div>
            @if($buyer->user_id === $user->id)
            <div>
                <a href="#" class="review__show">取引を完了する</a>
            </div>
            @endif
        </div>

        <!-- モーダル -->
        <div class="review__modal {{$buyer->buyer_reviewed ? 'show' : 'hidden'}}">
            @if($buyer->user_id === $user->id)
            <form class="review__modal--form" action="/buyer/review/{{$partner->id}}/{{$user->id}}/{{$item->id}}" method="post">
                @elseif($buyer->user_id !== $user->id)
                <form class="review__modal--form" action="/seller/review/{{$partner->id}}/{{$user->id}}/{{$item->id}}" method="post">
                    @endif
                    @csrf
                    <div class="review__title">
                        <h2>取引が完了しました。</h2>
                    </div>
                    <div class="review__content">
                        <p>今回の取引相手はどうでしたか？</p>
                        <div id="star-rating" class="star__area">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star star" data-value="{{$i}}"></i>
                                @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input">
                    </div>
                    <div class="review__btn">
                        <button>送信する</button>
                    </div>
                </form>
        </div>

        <!-- アイテム情報 -->
        <div class="item__info fixed">
            <div>
                <img src="{{\Storage::url($item->img_url)}}" alt="商品画像">
            </div>
            <div class="item__text">
                <p class="item__name">{{$item->name}}</p>
                <p class="item__price">&yen; {{$formattedPrice}}</p>
            </div>
        </div>

        <!-- メッセージ内容 -->
        <div class="message">
            @foreach($messages as $message)
            <!-- 相手側メッセージ -->
            @if($message->myself_id !== $user->id)
            <div class="partner" data-message-id="{{$message->id}}">
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
            <!-- 自分側メッセージ -->
            <div class="myself" data-message-id="{{$message->id}}">
                <div class="user__info">
                    <img src="{{asset('img/icon.png')}}">
                    <span>{{$message->myself->name}}</span>
                </div>

                <div class="message__body">
                    <p class="message__text">{{$message->message}}</p>
                    <form class="edit__form" action="/message/update/{{$message->id}}" method="POST" style="display: none;">
                        @csrf
                        @method('patch')
                        <textarea class="message__edit" name="message">{{ $message->message }}</textarea>
                        <button type="submit">更新</button>
                    </form>
                </div>

                @if(!empty($message->img_url))
                <div class="send__img">
                    <img src="{{\Storage::url($message->img_url)}}">
                </div>
                @endif
                <div class="myself__btn">
                    <div>
                        <button class="edit__toggle">編集</button>
                    </div>
                    <form action="/message/delete/{{$message->id}}" method="post">
                        @csrf
                        @method('delete')
                        <button>削除</button>
                    </form>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- メッセージ入力欄 -->
        <div class="send fixed">
            @error('message')
            <p class="error">{{$message}}</p>
            @enderror
            @error('img_url')
            <p class="error">{{$message}}</p>
            @enderror
            <form class="send__form" action="/send/{{$item->id}}/{{$user->id}}/{{$partner->id}}" method="post" enctype="multipart/form-data">
                @csrf
                <input class="send__message" type="text" name="message" placeholder="取引メッセージを記入してください" data-item-id="{{$item->id}}" data-user-id="{{$user->id}}">
                <label>
                    <input class="input__label" name="img_url" type="file" accept="image/png, image/jpeg">
                    画像を追加
                </label>
                <button class="send__btn" type="submit">
                    <img src="{{\Storage::url('img/send.jpg')}}" alt="送信">
                </button>
            </form>
        </div>
    </main>

    <!-- サイドバー -->
    <aside class="aside" fixed>
        <h2>その他の取引</h2>
        @foreach($otherDeals as $otherDeal)
        <a href="{{route('deal.index', $otherDeal->item->id)}}">{{$otherDeal->item->name}}</a>
        @endforeach
    </aside>
</div>

<script src="{{asset('js/deal.js')}}"></script>
@endsection