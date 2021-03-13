@extends('layouts.main')

@if (\Request::route()->getName() == 'home')
    @section('pageTitle','Loopz')
@else
    @section('pageTitle','Buscar imágenes - Loopz')
@endif

@section('hojasCSS')
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/buscador.css"/>
@endsection

@section('scripts')
    <script src="js/main.js"></script>
    <script src="js/autocomplete.js"></script>
    <script src="js/buscador.js"></script>
@endsection

@section('content')
    <div id="fondo" style="display:none"></div>
    <div class="container">
        <form id="formbuscador" class="form-group mt-md-5" method="get" autocomplete="off" action="{{ (\Request::route()->getName() == 'home') ? route('busqueda') : route('busquedaimages') }}">
            <div class="input-group d-flex justify-content-center {{ ($check3=="checked") ? '' : 'withborders' }}" id="grupobuscador" {{ ($check3=="checked") ? '' : 'style=top:100px' }}>
                <span id="logo" class="d-flex justify-content-center mr-md-5 mb-md-0 mb-1 col-md-3 col-xl-2"><img src="{{ (\Request::route()->getName() == 'home') ? 'images/logo.png' : 'images/logoimages.png' }}" /></span>
                <div id="input" class="col-xl-7 col-lg-6 col-md-5 col-sm-8 col-7 mt-md-3">
                    <input type="text" id="buscador" name="search" class="form-control form-control-lg">

                    <div id="suggestions"></div>
                </div>
                <button type="button" class="config input-group-append mt-md-3" data-toggle="modal" data-target="#modalConf">
                    <img src="{{ URL::to('/') }}/images/conf.png"/>
                </button>
                <div class="input-group-append col-sm-auto col-2 p-0">
                    <button class="btn btn-dark col-sm-auto col-10 px-sm-2 px-1 mt-md-3" id="botonsubmit">
                        <span class="spinner-border spinner-border text-light displaynone" id="cargando"></span>
                        <span id="iconbuscador" class="d-sm-none mt-1 d-block"><img src="images/buscador.png"/></span>
                        <span class="text-light d-sm-block d-none mt-1" id="submitlabel">Buscar</span>
                        <input type="submit" value="" />
                    </button>
                </div>
            </div>
            @include('modalbusqueda')
        </form>
        @if(Auth::check())
            <button id="tema" class="mb-5 mb-sm-3"><img class="sombras" src="{{ ($check3=="checked") ? URL::to('/').'/images/tema.png' : URL::to('/').'/images/tema2.png' }}"/></button>
        @endif
    </div>
@endsection
