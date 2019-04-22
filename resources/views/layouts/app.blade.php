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

        function hiddeAlert(){
            $('.alert').addClass("d-none");
        }
    </script>
</head>
<body>
    <div class="navbar-ofertz shadow">
        <nav class="navbar navbar-expand-md navbar-dark bg-danger">
            <div class="container">
                <a class="navbar-brand" href="/"><img src="{{ asset('img/logo.svg') }}" alt="..." class="img-fluid"></a>
                <form id="select-cidade" class="form-inline my-2 my-lg-0 pr-md-2 select-cidade">
                    <select id="select-cidade" class="custom-select my-1" id="inlineFormCustomSelectPref">
                        <option value="1">Alegrete-RS</option>
                        <option value="1">Erechim-RS</option>
                        <option selected>Santa Maria-RS</option>
                        <option value="2">Uruguaiana-RS</option>
                    </select>
                </form>
                <form class="form-inline my-2 my-lg-0 w-100">
                    <div class="input-group col px-0">
                        <input type="text" class="form-control" placeholder="Busque uma oferta!">
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                </form>
            </div>
        </nav>
        <nav id="nav-menu" class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <div id="nav-menu-content" class="mx-auto">
                    <a href="/" id="nav-inicial" class="nav-btn col px-2 px-sm-3"><i class="fas fa-home d-inline mr-md-2"></i><strong class="d-none d-md-inline">Inicial</strong></a>
                    <a href="/categorias" id="nav-categorias" class="nav-btn col px-2 px-sm-3"><i class="fas fa-th d-inline mr-md-2"></i><strong class="d-none d-md-inline">Categorias</strong></a>
                    <a href="/empresas" id="nav-empresas" class="nav-btn col px-2 px-sm-3"><i class="fas fa-store d-inline mr-md-2"></i><strong class="d-none d-md-inline">Empresas</strong></a>
                    <a href="/eventos" id="nav-eventos" class="nav-btn col px-2 px-sm-3"><i class="fas fa-glass-cheers d-inline mr-md-2"></i><strong class="d-none d-md-inline">Eventos</strong></a>
                    @guest
                        <a href="/login" id="nav-login" class="nav-btn col px-2 px-sm-3"><i class="fas fa-sign-in-alt d-inline mr-md-2"></i><strong class="d-none d-md-inline">Login</strong></a>
                    @else
                        <div class="nav-btn col dropdown d-inline px-2 px-sm-3">
                            <button class="nav-btn btn btn-link dropdown-toggle p-0" type="button" id="navbarDropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle d-inline mr-md-2"></i><strong class="d-none d-md-inline">{{ Auth::user()->name }}</strong></a>
                            </button>
                            <div class="nav-btn dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </div>

    <!-- CONTENT -->
    <div class="teatro">
        @yield('content')
    </div>
</body>
</html>
