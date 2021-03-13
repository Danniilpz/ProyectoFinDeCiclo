@extends('layouts.secondary')

@section('pageTitle','Historial - Loopz')

@section('content')
    <div class="container">
    @if(isset($registros))
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Búsqueda</th>
                <th scope="col">Tipo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
                <tr>
                    <th scope="row">@php echo date('H:i d/m/Y',strtotime($registro->fecha)) @endphp</th>
                    <td>{{$registro->busqueda}}</td>
                    <td>{{$registro->tipo}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row">
          <nav aria-label="paginas" class="col-md-6 col-12 d-flex justify-content-md-start justify-content-center">
              <ul class="pagination">
                  @if ($actual-3>=1)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page=1">1</a></li>
                  @endif
                  @if ($actual-2>=1)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$actual-2}}">{{$actual-2}}</a></li>
                  @endif
                  @if ($actual-1>=1)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$actual-1}}">{{$actual-1}}</a></li>
                  @endif
                  <li class="page-item active">
                      <a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$actual}}">{{$actual}} <span class="sr-only">(actual)</span></a>
                  </li>
                  @if ($actual+1<=$paginas)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$actual+1}}">{{$actual+1}}</a></li>
                  @endif
                  @if ($actual+2<=$paginas)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$actual+2}}">{{$actual+2}}</a></li>
                  @endif
                  @if ($actual+3<=$paginas)
                      <li class="page-item"><a class="page-link" href="{{ (\Request::route()->getName() == 'historial') ? 'historial' : 'historialcompleto' }}?page={{$paginas}}">{{$paginas}}</a></li>
                  @endif
              </ul>
          </nav>
          @if(\Request::route()->getName() == 'historial')
                  <a href="limpiarhistorial" class="text-md-right text-center btn btn-link col-md-6 col-12" >Limpiar Historial</a>
          @endif
        </div>
    @endif
    @if(isset($msg))
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{Session::get('success')}}
            </div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Búsqueda</th>
                <th scope="col">Tipo</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center" colspan="3">{{$msg}}</td>
                </tr>
            </tbody>
        </table>
    @endif
    </div>

@endsection
