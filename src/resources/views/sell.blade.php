@extends('layouts.default')

@section('title', '出品ページ')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/sell.css')  }}">
@endsection

@section('content')

@include('components.header')
<form action="/sell" method="post" class="sell center" enctype="multipart/form-data">
    @csrf
    <h1 class="page__title">商品の出品</h1>

    <!-- 画像 -->
    <p class="entry__name">商品画像</p>
    <div class="sell__img">
        <img class="appload__img" id="myImage">
    </div>
    <div class="select_image--btn">
        <label class="btn2">
            画像を選択する
            <input id="target" name="img_url" class="btn2--input" type="file" accept="image/png, image/jpeg">
        </label>
    </div>
    <div class="form__error">
        @error('img_url')
            {{$message}}
        @enderror
    </div>

    <h2 class="heading__name">商品の詳細</h2>

    <!-- カテゴリー -->
    <p class="entry__name">カテゴリー</p>
    <div class="sell__categories">
        @foreach($categories as $category)
        <div class="sell__category">
            <input type="checkbox" name="categories[]" id="{{$category->id}}" class="sell__check" value="{{$category->id}}">
            <label for="{{$category->id}}" class="sell__check-label">{{$category->category}}</label>
        </div>
        @endforeach
    </div>
    <div class="form__error">
        @error('categories')
            {{$message}}
        @enderror
    </div>
   
    <!-- 商品の状態 -->
    <label for="status" class="entry__name">商品の状態</label>
    <select name="condition_id" id="status" class="sell__select input">
        <option value="null" hidden>選択してください</option>
        @foreach($conditions as $condition)
        <option value="{{$condition->id}}">{{$condition->condition}}</option>
        @endforeach
    </select>
    <div class="form__error">
        @error('condition_id')
            {{$message}}
        @enderror
    </div>

    <h2 class="heading__name">商品名と説明</h2>

    <!-- 商品名 -->
    <label for="name" class="entry__name">商品名</label>
    <input type="text" name="name" id="name" class="input">
    <div class="form__error">
        @error('name')
            {{$message}}
        @enderror
    </div>

    <!-- 商品説明 -->
    <label for="description" class="entry__name">説明</label>
    <textarea name="description" id="description" class="input textarea"></textarea>
    <div class="form__error">
        @error('description')
            {{$message}}
        @enderror
    </div>

    <label for="price" class="entry__name">販売価格</label>
    <input type="number" id="price" name="price" class="input">
    <div class="form__error">
        @error('price')
            {{$message}}
        @enderror
    </div>

    <button class="btn btn--big">出品する</button>
</form>

<script>
const target = document.getElementById('target');
const e = document.getElementById('appload');
target.addEventListener('change', function (e) {
    const file = e.target.files[0]
    const reader = new FileReader();
    reader.onload = function (e) {
        const img = document.getElementById("myImage")
        img.src = e.target.result;
    }
    reader.readAsDataURL(file);
}, false);
</script>

@endsection