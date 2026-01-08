<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@hasSection('title')@yield('title') - @endif {{ config('app.name') }}</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>

  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->

  <!-- <link rel="shortcut icon" href="{{ asset('img/favicon.ico').'?'.time() }}">
  <link rel="stylesheet" href="{{ asset('css/fonts.css').'?'.time() }}">
  <link rel="stylesheet" href="{{ asset('css/style.css').'?'.time() }}"> -->
  @yield('page_css')

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <!-- <script type="text/javascript" src="{{ asset('js/main.js').'?'.time() }}"></script> -->
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <div class="container">
    <header class="header">
      <h1 class="headerLogo">
        <a href="/" class="headerLogo__link"><img src="" alt="" class="headerLogo__image"></a>
      </h1>
      <nav class="headerNavi js--navi">
        <ul class="headerNaviList">
          <li class="headerNaviList__item layout--sp"><a href="/" class="headerNaviList__link">ホーム</a></li>
          @if(Auth::check())
          <li class="headerNaviList__item layout--sp">
            <a href="/notice" class="headerNaviList__link">通知</a>
            @if(isset($noncheck_count)){{$noncheck_count}}@endif
          </li>
          <li class="headerNaviList__item"><a href="/profile/{{ $user->id }}" class="headerNaviList__link">プロフィール</a></li>
          <li class="headerNaviList__item"><a href="/edit/{{ $user->id }}" class="headerNaviList__link">編集</a></li>
          <li class="headerNaviList__item"><a href="/logout" class="headerNaviList__link">ログアウト</a></li>
          @endif
          <li class="headerNaviList__item layout--sp js--close"><span class="headerNaviList__close">閉じる</span></li>
        </ul>
      </nav>
      <button class="headerMenu layout--sp js--menu">
        <span class="headerMenuLine"></span>
      </button>
    </header>

    <main class="main">
      @yield('content')
    </main>

    <footer class="footer">
      <div class="footerBottom">
        <ol class="topicPath">
          <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="topicPath__item">
            <a itemprop="item" href="/" class="topicPath__link">
              <span itemprop="name" class="topicPath__name">ホーム</span>
            </a>
            <meta itemprop="position" content="1" />
          </li>
          @yield('breadcrumb')
        </ol>
        <div class="footerLogo">
          <a href="/" class="footerLogo__link">
            <img src="" alt="" class="footerLogo__image">
          </a>
        </div>
      </div>
    </footer>
  </div>
  @yield('page_js')
</body>

</html>
