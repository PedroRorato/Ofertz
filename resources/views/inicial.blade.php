@extends('layouts.app')
@section('title') Inicial @endsection
@section('button') #nav-inicial @endsection
@section('content')
<div style="background: url('https://ofertz.com.br/assets/img/back.jpg') center no-repeat; background-size: cover;">
    <div class="container row mx-auto justify-content-between pb-5" style="padding-top: 2.5rem;">
        <div class="col-md-8 row align-items-center">
            <div class="">
                <h1 class="d-none d-lg-block display-4 rns-semibold pb-4">PROCURANDO DESCONTOS?</h1>
                <h1 class="d-block d-lg-none rns-semibold pb-4">PROCURANDO1 DESCONTOS?</h1>
                <h4>Aqui você descobre as melhores ofertas de Santa Maria!</h4>
                <div class="row mx-0 pt-3">
                    <button type="button" class="btn btn-outline-danger btn-lg mr-4"><i class="fab fa-lg fa-android d-inline mr-2"></i>Android</button>
                    <button type="button" class="btn btn-outline-danger btn-lg"><i class="fab fa-lg fa-apple d-inline mr-2"></i>iOS</button>
                </div>
            </div>
        </div>
        <div class="d-none d-md-block col-md-4 text-lg-right">
            <div class="card" style="background: none; border: none">
                <img class="card-img-top" src="{{ asset('img/app.png') }}">
            </div>
        </div>

    </div>
    <div class="row m-0 pt-3 pb-2 bg-light">
        <h4 class="mx-auto rns-bold" style="color:#8d1913;"><strong>CATEGORIAS</strong></h4>
    </div>

</div>
    
@endsection
