@extends('layouts.default')

@section('title', '商品詳細ページ')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
@endsection

@section('content')

@include('components.header')
<div class="container">
    <div class="item">
        @if($item->sold())
        <div class="item__img sold">
            <img src="{{ \Storage::url($item->img_url) }}" alt="商品画像">
        </div>
        @else
        <div class="item__img">
            <img src="{{ \Storage::url($item->img_url) }}" alt="商品画像">
        </div>
        @endif

        <div class="item__info">
            <h2 class="item__name">{{$item->name}}</h2>
            <p class="item__price">￥ {{number_format($item->price)}}</p>

            <div class="item__form">
                <form action="{{ $item->liked() ? '/item/unlike/'.$item->id : '/item/like/'.$item->id  }}" method="post" class="item__like" id="like__form">
                <!-- $item->liked()がtrueのとき/item/unlike/、falseのとき/item/like/へ行く -->
                <!-- 文字列と変数なのでドットで繋いでいる、'/item/unlike/5'このように変換される --> 
                    @csrf
                    <button>
                        <i class="fa-2xl fa-heart {{ $item->liked() ? 'fa-sharp fa-solid' : 'fa-regular' }}"></i>
                        <!-- $item->liked()がtrueのときfa-sharp fa-solid（塗りつぶしアイコン） -->
                        <!-- $item->liked()がfalseのときfa-regular（輪郭のみアイコン） -->
                    </button>
                    <p class="like__count">{{$item->likeCount()}}</p>
                </form>
                <div class="item__comment">
                    <a href="#comment"><i class="fa-regular fa-comment fa-2xl"></i></a>
                    <p class="comment__count">{{$item->getComments()->count()}}</p>
                </div>
            </div>

            @if($item->sold())
            <a href="#" class="btn item__purchase disable" disabled>売り切れました</a>
            <!-- href="#"はリンクを無効化するためのHTML -->
            <!-- disableはボタンをグレーアウトしたり、クリックできないように設定するためのCSSクラス -->
            @elseif($item->mine())
            <!-- この商品が、自分が出品したものだったら -->
            <a href="#" class="btn item__purchase disable" disabled>購入できません</a>
            @else
            <a href="/purchase/{{$item->id}}" class="btn item__purchase">購入手続きへ</a>
            @endif

            <h3 class="item__section">商品説明</h3>
            <p>{{$item->description}}</p>

            <h3 class="item__section">商品情報</h3>
            <table class="item__table">
                <tr>
                    <th>カテゴリー</th>
                    <td>
                        <ul class="item__table-category">
                            @foreach($item->categories() as $category)
                            <li class="category__btn">{{$category->category}}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td>{{$item->condition->condition}}</td>
                </tr>
            </table>

            <div id="comment" class="comment__section">
                <h3 id="count__title">コメント({{ $item->getComments()->count() }})</h3>
                <div class="comments" id="comments__list">
                    @foreach($item->getComments() as $comment)
                    <div class="comment">
                        <div class="comment__user">
                            <div class="user__img">
                                <img src="{{\Storage::url($comment->user->profile->img_url)}}" alt="">
                            </div>
                            <p class="{{$comment->user->name}}"></p>
                        </div>
                        <p class="comment__content">{{$comment->comment}}</p>
                    </div>
                    @endforeach
                </div>

                <form action="/item/comment/{{$item->id}}" method="post" class="comment__form" id="comment__form">
                    @csrf
                    <p class="comment__form-title">商品へのコメント</p>
                    <textarea name="comment" id="comment__textarea" cols="30" rows="10" class="comment__form-textarea"></textarea>
                    <!-- cols="30" rows="10"：横幅30文字分、縦の行数10行分 -->
                    <div class="form__error">
                        @error('comment')
                        {{$message}}
                        @enderror
                    </div>
                    <button class="btn comment__form-btn">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/json.js') }}"></script>
@endsection