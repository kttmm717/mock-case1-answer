@extends('layouts.default')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@include('components.header')
<div class="container">

    <div class="user">
        <div class="user__info">
            <div class="user__img">
                @if(isset($user->profile->img_url))
                <img class="user__icon" src="{{ \Storage::url($user->profile->img_url) }}" alt="">
                @else
                <img id="myImage" class="user__icon" src="{{ asset('img/icon.png') }}" alt="">
                @endif
            </div>
            <div>
                <p class="user__name">{{$user->name}}</p>
                @if(!empty($averageRatingRound))
                @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star star {{$i <= $averageRatingRound ? 'active' : ''}}" data-value="{{$i}}"></i>
                @endfor
                @endif
            </div>
            <div class="mypage__user--btn">
                <a class="btn2" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
    </div>

    <div class="border">
        <ul class="border__list">
            <li><a class="list {{request('page') === 'sell' ? 'active' : ''}}" href="/mypage?page=sell">出品した商品</a></li>
            <li><a class="list {{request('page') === 'buy' ? 'active' : ''}}" href="/mypage?page=buy">購入した商品</a></li>
            <li>
                <a class="list {{request('page') === 'deal' ? 'active' : ''}}" href="/mypage?page=deal">取引中の商品</a>
                @if(!empty($totalUnreadCount))
                <span class="total__badge">{{$totalUnreadCount}}</span>
                @endif
            </li>
        </ul>
    </div>

    <div class="items">
        @foreach($items as $item)
        <div class="item">
            @if(request('page') === 'deal')
            <a href="/deal/{{$item->id}}">
            @else
            <a href="/item/{{$item->id}}">
            @endif

            @if($item->sold() && request('page') === 'sell')
            <div class="item__img--container sold">
                <img class="item__img" src="{{\Storage::url($item->img_url)}}" alt="商品画像">
            </div>
            @elseif(!empty($unreadCounts) && request('page') === 'deal')
            <div class="item__img--container count">
                <img class="item__img" src="{{\Storage::url($item->img_url)}}" alt="商品画像">
                @php
                    $unread = $unreadCounts->firstWhere('item_id', $item->id);
                    $unreadCount = $unread ? $unread->unread_count : 0;
                @endphp
                @if($unreadCount > 0)
                    <span class="badge">{{$unreadCount}}</span>
                @endif
            </div>
            @else
            <div class="item__img--container">
                <img class="item__img" src="{{\Storage::url($item->img_url)}}" alt="商品画像">
            </div>
            @endif
        </a>
        <p class="item__name">{{$item->name}}</p>
        </div>
        @endforeach
    </div>
</div>

<script src="{{asset('js/mypage.js')}}"></script>
@endsection