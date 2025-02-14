<header class="header">

    <!-- ロゴ -->
    <div class="header__logo">
        <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="ロゴ"></a>
    </div>

    @if( !in_array(Route::currentRouteName(), ['register', 'login', 'verification.notice']) )
    <!--現在のルートに[]内の文字列がが含まれていなければ検索フォームを表示するという意味 -->
    <form class="header_search" action="/" method="get">
        @csrf
        <input id="inputElement" class="header_search--input" type="text" name="search" placeholder="なにをお探しですか？">
        <button id="buttonElement" class="header_search--button">
            <img src="{{ asset('img/search_icon.jpeg') }}" alt="検索アイコン" style="height:100%;">
        </button>
    </form>

    <!-- ナビゲーションメニュー -->
    <nav class="header__nav">
        <ul>
            <!-- ログインしている場合はナビゲーションメニュー表示 -->
            @if(Auth::check())
            <li>
                <form action="/logout" method="post">
                    @csrf
                    <button class="header__logout">ログアウト</button>
                </form>
            </li>
            <li><a href="/mypage">マイページ</a></li>
            @else
            <li><a href="/login">ログイン</a></li>
            <li><a href="/register">会員登録</a></li>
            @endif
            
            <a href="/sell">
                <li class="header__btn">出品</li>
            </a>
        </ul>
    </nav>
    @endif
</header>