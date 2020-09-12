<!DOCTYPE html>
    <html lang="ja">

    <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    @if(app('env') == 'local')
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    @else
        <link href="{{ secure_asset('vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    @endif
    
    <!-- Custom fonts for this template -->
    @if(app('env') == 'local')
        <link href="{{ secure_asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ secure_asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    @else
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    @endif

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    @if(app('env') == 'local')
        <link href="{{ asset('css/landing-page.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ secure_asset('css/landing-page.min.css') }}" rel="stylesheet">    
    @endif

    </head>
    <body>
        <div class="stricky-top">
            <p>{{ app('env') }}</p>
            @section('stricky-top')
            @include('components.navbar')
            @show
        </div>
        <div class="content" style="width: 100%; max-width: 
            90%;padding-left: 15px;margin-left: auto;
            margin-top: 45px;margin-bottom: 45px;margin-right: auto;">
            @yield('content')
        </div>

    </body>
</html>