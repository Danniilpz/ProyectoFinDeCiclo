<nav aria-label="paginas">
    <ul class="pagination">
        @php
            $url=((\Request::route()->getName() == 'busqueda') ? route('busqueda') : route('busquedaimages'))."?search=".$search;
            $parametros=(($detalles == true) ? '&detalles=on' : '');
            if(\Request::route()->getName() == 'busqueda'){
                $parametros.=(($vistaprevia == true) ? '&vistaprevia=on' : '');
            }
        @endphp
        @if ($actual-3>=1)
            <li class="page-item"><a class="page-link" href="{{$url}}&page=1{{$parametros}}">1</a></li>
        @endif
        @if ($actual-2>=1)
            <li class="page-item"><a class="page-link" href="{{$url}}&page={{$actual-2}}{{$parametros}}">{{$actual-2}}</a></li>
        @endif
        @if ($actual-1>=1)
            <li class="page-item"><a class="page-link" href="{{$url}}&page={{$actual-1}}{{$parametros}}">{{$actual-1}}</a></li>
        @endif
        <li class="page-item active">
            <a class="page-link" href="{{$url}}&page={{$actual}}{{$parametros}}">{{$actual}} <span class="sr-only">(actual)</span></a>
        </li>
        @if ($actual+1<=$paginas)
            <li class="page-item"><a class="page-link" href="{{$url}}&page={{$actual+1}}{{$parametros}}">{{$actual+1}}</a></li>
        @endif
        @if ($actual+2<=$paginas)
            <li class="page-item"><a class="page-link" href="{{$url}}&page={{$actual+2}}{{$parametros}}">{{$actual+2}}</a></li>
        @endif
        @if ($actual+3<=$paginas)
            <li class="page-item"><a class="page-link" href="{{$url}}&page={{$paginas}}{{$parametros}}">{{$paginas}}</a></li>
        @endif
    </ul>
</nav>
