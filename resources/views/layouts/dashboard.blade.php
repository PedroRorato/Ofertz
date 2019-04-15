<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ofertz - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    <!-- Scripts Custom -->
    <script type="text/javascript">
        $(window).on('load', function() {
            $("@yield('button')").addClass("text-danger");
        });

        var lastScrollTop = 0;
        /*Efeito Header*/
        $(window).on('scroll', function () {
            var st = $(this).scrollTop();
            if (st > lastScrollTop){
                // downscroll code
                $("#nav-menu").addClass("d-none");
            } else {
                // upscroll code
                $("#nav-menu").removeClass("d-none");
            }
            lastScrollTop = st;
        });
    </script>
</head>
<body>
    <div class="row mx-0">
        <div id="menu" class="col text-white bg-dark" style="height: 100vh; width: 250px">
            Menu
        </div>
        <div class="col" style="">
            
        </div>
    </div>
</body>
</html>
