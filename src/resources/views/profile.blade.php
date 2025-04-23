@extends('layouts.default')

@section('title', 'プロフィール設定')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/profile.css')  }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.header')
<form action="/mypage/profile" method="post" class="profile center" enctype="multipart/form-data">
    @csrf
    <h1 class="page__title">プロフィール設定</h1>
    <div class="user">
        <div class="user__img">
            @if(isset($profile->img_url))
                <img class="user__icon" src="{{ \Storage::url($profile->img_url) }}" alt="">
            @else
                <img id="myImage" class="user__icon" src="{{ asset('img/icon.png') }}" alt="">
            @endif
        </div>
        <div class="profile__user--btn">
            <label class="btn2">
                画像を選択する
                <input id="target" class="btn2--input" type="file" name="img_url" accept="image/png, image/jpeg">
            </label>
        </div>
    </div>

    <label for="name" class="entry__name">ユーザー名</label>
    <input type="text" id="name" name="name" class="input" value="{{Auth::user()->name}}">
    <div class="form__error">
        @error('name')
            {{$message}}
        @enderror
    </div>

    <label for="postcode" class="entry__name">郵便番号</label>
    <input type="text" id="postcode" name="postcode" class="input" value="{{ $profile ? $profile->postcode : '' }}" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');">
    <div class="form__error">
        @error('postcode')
            {{$message}}
        @enderror
    </div>

    <label for="address" class="entry__name">住所</label>
    <input type="text" id="address" name="address" class="input" value="{{ $profile ? $profile->address : '' }}">
    <div class="form__error">
        @error('address')
            {{$message}}
        @enderror
    </div>

    <label for="building" class="entry__name">建物名</label>
    <input type="text" id="building" name="building" class="input" value="{{ $profile ? $profile->building : '' }}">

    <button class="btn btn--big">更新する</button>
</form>

<!-- 画像プレビュー機能 -->
<script>
const target = document.getElementById('target');
// id="target"の要素(ここでは<input type="file">)を取得して定数targetに格納

target.addEventListener('change', function (e) {
// changeイベント(ユーザーがファイルを選択)が発生したとき、処理を実行

    const file = e.target.files[0]
    //イベントが発火した要素の最初のファイルを取得して定数fileに格納
    
    const reader = new FileReader();
    // FileReaderオブジェクト作成
    // ファイルを読み込んでその内容をデータURLに変換するために使用

    reader.onload = function (e) {
    // onloadはファイルの読み込みが完了したときに発火するイベント

        const img = document.getElementById("myImage");
        // id="myImage"の要素を取得して定数imgに格納

        console.log(img.src);
        // 変更前のimgのsrcをログに出力（まだ画像がセットされていないはず）

        img.src = e.target.result;
        // 読み込んだ画像のデータURLをsrcに設定し、画像プレビューを表示

        console.log(img.src);
        // 変更後のimgのsrcをログに出力（新しい画像のデータURLが表示される）
    }
    reader.readAsDataURL(file);
    // fileをデータURL形式に変換して読み込む
    // 読み込みが完了するとreader.onloadが発火する

}, false);
// addEventListenerの第三引数、今回の場合は省略可能
// イベントの伝播方法を指定するuseCapture（キャプチャフェーズを使用するかどうか）の値

</script>
@endsection