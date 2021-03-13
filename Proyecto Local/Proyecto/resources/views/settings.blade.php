@extends('layouts.secondary')

@section('pageTitle','Configuración - Loopz')

@section('hojasCSS')
    <link rel="stylesheet" href="css/forms.css"/>
@endsection

@section('scripts')
    <script src="js/forms.js"></script>
@endsection

@section('content')
<div class="container">
    <h2>Configuración y preferencias</h2>
    <hr/>
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{Session::get('success')}}
        </div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{Session::get('error')}}
        </div>
    @endif
    <div class="row">
        <div class="form-group col-lg-4 col-md-6 col-12 card">
            <div class="card-header">
                <h4 class="text-center">Cambiar nombre</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-primary text-center"><b>Nombre actual</b>: {{Auth::user()->name}}</div>
                <form method="post" action="cambianombre">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input class="form-control" placeholder="Nuevo nombre" type="text" id="newname" name="newname" required/>
                        <div class="input-group-append">
                            <input class=" btn btn-primary" type="submit" value="Confirmar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="form-group col-lg-4 col-md-6 col-12 card">
            <div class="card-header">
                <h4 class="text-center">Cambiar email</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-primary text-center"><b>Email actual</b>: {{Auth::user()->email}}</div>
                <form method="post" action="cambiaemail">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input class="form-control" placeholder="Nuevo email" type="email" id="newemail" name="newemail" required/>
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="submit" value="Confirmar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="form-group col-lg-4 col-12 card">
            <div class="card-header">
                <h4 class="text-center">Cambiar contraseña</h4>
            </div>
            <div class="card-body">
                <form method="post" action="cambiacontrasena">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-lg-12 col-md-4 col-12 mb-2">
                            <input class="form-control" placeholder="Antigua contraseña" type="password" id="oldpass" name="oldpass" required/>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-2">
                            <input class="form-control" placeholder="Nueva contraseña" type="password" id="newpass" name="newpass" required/>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-2">
                            <input class="form-control" placeholder="Repite nueva contraseña" type="password" id="renewpass" name="renewpass" required/>
                        </div>
                    </div>
                    <input class="col-12 btn btn-primary" type="submit" value="Confirmar"/>
                </form>
            </div>
        </div>
    </div>
    <div class="form-group card">
        <div class="card-header">
            <h4 class="text-center">Preferencias de búsqueda</h4>
        </div>
        <div class="card-body">
            <form method="post" action="preferencias">
                {{ csrf_field() }}
                <div class="form-row d-flex justify-content-center">
                    <div class="col-lg-3 col-md-4 mb-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="detalles" class="custom-control-input" id="switch1" {{$check1}}>
                            <label class="custom-control-label" for="switch1">Búsqueda detallada</label>
                            <button type="button" class="interrogacion" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="Si marcas búsqueda detallada, el motor de búsqueda hará una busqueda en la red de palabras iguales y similares a las que buscas. Esto puede reducir la velocidad de búsqueda."></button>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 mb-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="vistaprevia" class="custom-control-input" id="switch2" {{$check2}}>
                            <label class="custom-control-label" for="switch2">Vistas previas</label>
                            <button type="button" class="interrogacion" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="Si marcas vistas previas, el motor de búsqueda mostrará un pequeño párrafo debajo de cada resultado. Esto puede reducir la velocidad de búsqueda."></button>
                        </div>
                    </div>
                    <input  class="btn btn-primary" type="submit" value="Confirmar"/>
                </div>

            </form>
        </div>
    </div>
    <div class="form-group card">
        <div class="card-header">
            <h4 class="text-center">Modificar tema</h4>
        </div>
        <div class="card-body">
            <form method="post" action="cambiartema" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-md-12 mb-2 d-flex justify-content-center">
                    <div class="custom-control custom-switch">
                        <input class="custom-control-input" type="checkbox" id="fondoactivo" name="fondoactivo" {{$check3}}>
                        <label class="custom-control-label" for="fondoactivo">Mostrar fondos de pantalla</label>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="custom-file">
                        <input type="file" name="fondo" class="custom-file-input" id="fondo" disabled="disabled" accept="image/x-png,image/gif,image/jpeg">
                        <label class="custom-file-label" for="fondo" data-browse="Examinar">Selecciona una imagen...</label>
                    </div>
                </div>
                <div class="row mb-3">
                    @php
                        if(strpos($fondos_activos,"1")!==false){
                            $active1="checked";
                        }
                        else{
                            $active1="";
                        }
                        if(strpos($fondos_activos,"2")!==false){
                            $active2="checked";
                        }
                        else{
                            $active2="";
                        }
                        if(strpos($fondos_activos,"3")!==false){
                            $active3="checked";
                        }
                    else{
                            $active3="";
                        }
                    @endphp
                    <div class='fondo col-md-3 col-sm-4 col-6'><input type="checkbox" name="fondo1" class="fondocheck" id="fondo1" {{$active1}}/><label for="fondo1"><img src='images/fondo1.jpg'/></label></div>
                    <div class='fondo col-md-3 col-sm-4 col-6'><input type="checkbox" name="fondo2" class="fondocheck" id="fondo2" {{$active2}}/><label for="fondo2"><img src='images/fondo2.jpg'/></label></div>
                    <div class='fondo col-md-3 col-sm-4 col-6'><input type="checkbox" name="fondo3" class="fondocheck" id="fondo3" {{$active3}}/><label for="fondo3"><img src='images/fondo3.jpg'/></label></div>
                    @for($i=1,$j=4;$i<=$num_images;$i++,$j++)
                        @php
                        if(strpos($fondos_activos,(string)$j)!==false){
                            $active="checked";
                        }else{
                            $active="";
                        }
                        @endphp
                        <div class='fondo col-md-3 col-sm-4 col-6'><input type="checkbox" name="fondo{{$j}}" class="fondocheck" id="fondo{{$j}}" {{$active}}/><label for="fondo{{$j}}"><img src='imagen/{{$i}}'/><a href="borrarimagen?img={{$i}}"><img src="images/borrar.png"/></a></label></div>
                    @endfor
                </div>
                <div class="col-md-12 d-flex justify-content-center">
                    <input  class="btn btn-primary" type="submit" value="Confirmar"/>
                </div>
            </form>
        </div>
    </div>
    <div class="form-group card">
        <div class="card-body">
            <form method="post" action="eliminarcuenta">
                {{ csrf_field() }}
                <div class="col-md-12 d-flex justify-content-center">
                    <input class="btn btn-danger" type="submit" value="Eliminar mi cuenta"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
