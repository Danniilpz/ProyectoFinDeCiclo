@extends('layouts.secondary')

@section('pageTitle','Configuración - Loopz')

@section('hojasCSS')
    <link rel="stylesheet" href="css/forms.css"/>
@endsection


@section('content')
    <div class="container text-center">
        <div class="form-group">
            <h5>¿Estás seguro? Tu cuenta se borrará permanentemente. Esta acción es irreversible.</h5>
            <form method="post" action="eliminarcuenta?confirm=true">
                {{ csrf_field() }}
                <input class="btn btn-danger" type="submit" value="Estoy seguro de eliminar mi cuenta"/>
            </form>
        </div>
    </div>
@endsection