<!DOCTYPE html>
    <html lang="ja">

    <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    @if(app('env') == 'local')
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    @else
        <link href="{{ secure_asset('vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    @endif
    
    <!-- Custom fonts for this template -->
    @if(app('env') == 'local')
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    @else
        <link href="{{ secure_asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ secure_asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    @endif

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    @if(app('env') == 'local')
        <link href="{{ asset('css/landing-page.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ secure_asset('css/landing-page.min.css') }}" rel="stylesheet">    
    @endif

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
    <script src="https://unpkg.com/chartjs-plugin-colorschemes"></script>

    <style>
    code {
        font-family: Consolas,"courier new";
        color: crimson;
        background-color: #f1f1f1;
        padding: 2px;
        font-size: 105%;
      }
      </style>


    </head>
    <body>
        <div class="stricky-top">
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