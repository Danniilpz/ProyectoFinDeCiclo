@extends('layouts.main')

@section('pageTitle','Verificación de email - Loopz')

@section('hojasCSS')
    <link rel="stylesheet" href="../css/main.css"/>
    <link rel="stylesheet" href="../css/auth.css"/>
@endsection

@php
    $fondos_activos="123";
    $check3="checked";

@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-light">
                <div class="card-header"><div class="d-flex justify-content-center"><a href="{{ route('home') }}"><img id="minilogo" src="../images/logo.png" alt=""></a></div><h4 class="text-center">{{ __('Verificar email') }}</h4></div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un link de verificación a tu dirección de correo electrónico.') }}
                        </div>
                    @endif

                    {{ __('Antes de continuar, por favor comprueba si has recibido el link de verificación en tu email.') }}
                    {{ __('Si no lo has recibido') }}, <a href="{{ route('verification.resend') }}">{{ __('haz click aquí para reenviarlo') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
