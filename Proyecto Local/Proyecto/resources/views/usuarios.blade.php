@extends('layouts.secondary')

@section('pageTitle','Listado de usuarios - Loopz')

@section('content')
    <div class="container">
      @if(Session::has('success'))
          <div class="alert alert-success" role="alert">
              {{Session::get('success')}}
          </div>
      @elseif(Session::has('error'))
          <div class="alert alert-danger" role="alert">
              {{Session::get('error')}}
          </div>
      @elseif(Session::has('warning'))
          <div class="alert alert-warning" role="alert">
              {{Session::get('warning')}}
          </div>
      @endif
    @if(isset($registros))
        <table class="table">
            <thead>
            <tr>
                <th class="text-center" scope="col">ID</th>
                <th class="text-center" scope="col">Email</th>
                <th class="text-center" scope="col">Nombre</th>
                <th class="text-center" scope="col"></th>
                <th class="text-center" scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
                <tr>
                    <th scope="row" class="align-middle text-center">{{$registro->id}}</th>
                    <td class="align-middle text-center">{{$registro->email}}</td>
                    <td class="align-middle text-center">{{$registro->name}}</td>
                    <td class="align-middle text-center">
                      @if($registro->email_verified_at==NULL)
                        No verificado [<a href="verificarusuario?user={{$registro->id}}">Verificar</a>]
                      @else
                        Verificado
                      @endif
                    </td>
                    <td><a href="eliminarusuario?user={{$registro->id}}" class="text-danger">Eliminar</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <nav aria-label="paginas">
            <ul class="pagination">
                @if ($actual-4>=1)
                    <li class="page-item"><a class="page-link" href="listadousers?page=1">1</a></li>
                @endif
                @if ($actual-3>=1)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual-3}}">{{$actual-3}}</a></li>
                @endif
                @if ($actual-2>=1)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual-2}}">{{$actual-2}}</a></li>
                @endif
                @if ($actual-1>=1)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual-1}}">{{$actual-1}}</a></li>
                @endif
                <li class="page-item active">
                    <a class="page-link" href="listadousers?page={{$actual}}">{{$actual}} <span class="sr-only">(actual)</span></a>
                </li>
                @if ($actual+1<=$paginas)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual+1}}">{{$actual+1}}</a></li>
                @endif
                @if ($actual+2<=$paginas)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual+2}}">{{$actual+2}}</a></li>
                @endif
                @if ($actual+3<=$paginas)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$actual+3}}">{{$actual+3}}</a></li>
                @endif
                @if ($actual+4<=$paginas)
                    <li class="page-item"><a class="page-link" href="listadousers?page={{$paginas}}">{{$paginas}}</a></li>
                @endif
            </ul>
        </nav>
    @endif
    </div>

@endsection
