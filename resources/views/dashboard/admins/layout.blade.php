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

        function showMenu(){
            $('#menu').css('top', '0');
        }
        function hiddeMenu(){
            $('#menu').css('top', '-100vh');
        }
    </script>
</head>
<body class="dash-body">
    <div id="menu" class="bg-dark px-0 pb-5">
        <div class="row mx-0 px-5 menu-logo d-none d-md-block">
            <img src="{{ asset('img/logo.svg') }}" alt="..." class="img-fluid menu-logo-img">
        </div>
        <div class="row mx-0 px-5 menu-logo d-block d-md-none py-2 text-center" onclick="hiddeMenu()">
            <button class="btn btn-outline-light d-md-none"><i class="fas fa-times"></i></button>
        </div>
        <div class="accordion" id="menu-accordion">
            <div class="card card-menu-profile">
                <div class="card-header card-header-menu row mx-0" data-toggle="collapse" data-target="#collapseProfile" aria-expanded="false">
                    <div class="col-3 px-0">
                        <img src="{{ asset('img/profile.jpg') }}" alt="..." class="rounded-circle img-fluid">
                    </div>
                    <div class="col-8 pr-0">
                        Pedro Berleze Rorato
                    </div>
                    <div class="col-1 px-0">
                        <i class="fas fa-chevron-down pt-1"></i>
                    </div>
                </div>
                <div id="collapseProfile" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-user mr-2"></i>Conta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <a href="" id="dash-menu-inicial" class="card-header card-header-menu color-menu">
                    <i class="fas fa-home mr-2"></i>Inicial
                </a>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu" data-toggle="collapse" data-target="#collapseAdministradores" aria-expanded="false">
                    <i class="fas fa-gavel mr-2"></i>Administradores<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseAdministradores" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu" data-toggle="collapse" data-target="#collapseCategorias" aria-expanded="false">
                    <i class="fas fa-th mr-2"></i>Categorias<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseCategorias" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu" data-toggle="collapse" data-target="#collapseCidades" aria-expanded="false">
                    <i class="fas fa-city mr-2"></i>Cidades<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseCidades" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu" data-toggle="collapse" data-target="#collapseCompras" aria-expanded="false">
                    <i class="fas fa-shopping-cart mr-2"></i>Compras<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseCompras" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseEmpresas" aria-expanded="false">
                    <i class="fas fa-store mr-2"></i>Empresas<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseEmpresas" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseEventos" aria-expanded="false">
                    <i class="fas fa-glass-cheers mr-2"></i>Eventos<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseEventos" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseFotos" aria-expanded="false">
                    <i class="fas fa-image mr-2"></i>Fotos<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseFotos" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseFranqueados" aria-expanded="false">
                    <i class="fas fa-handshake mr-2"></i>Franqueados<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseFranqueados" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseOfertas" aria-expanded="false">
                    <i class="fas fa-tag mr-2"></i>Ofertas<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseOfertas" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseProdutos" aria-expanded="false">
                    <i class="fas fa-gifts mr-2"></i>Produtos<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseProdutos" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-menu">
                <div class="card-header card-header-menu dropdown" data-toggle="collapse" data-target="#collapseUsuarios" aria-expanded="false">
                    <i class="fas fa-users mr-2"></i>Usuários<i class="fas fa-chevron-down pt-1"></i>
                </div>
                <div id="collapseUsuarios" class="collapse" aria-labelledby="headingThree" data-parent="#menu-accordion">
                    <ul class="nav flex-column menu-options">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-plus mr-2"></i>Adicionar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list mr-2"></i>Listagem</a>
                        </li>
                    </ul>
                </div>
            </div>                
        </div>
    </div>
    <!-- END MENU -->

    <!-- DASH -->
    <div class="dash">
        <div class="dash-container">
            <header  class="shadow-sm" >
                <nav class="navbar navbar-light bg-white pb-0">
                    <h2 class="mb-1 pt-2">Inicial</h2>
                    <button class="btn btn-outline-dark d-md-none" onclick="showMenu()"><i class="fas fa-bars"></i></button>
                </nav>
                <nav aria-label="breadcrumb ">
                    <ol class="breadcrumb py-1">
                        <li class="breadcrumb-item"><a href="#">Inicial</a></li>
                        <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listagem</li>
                    </ol>
                </nav>
            </header>
            <!-- DASH CONTENT -->
            <div class="px-3 pt-1 pb-3">
                <div class="card">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- END DASH CONTENT -->
        </div>
        Copyright Bergard Company © 2019
    </div>
    <!-- END DASH -->


       
</body>
</html>
