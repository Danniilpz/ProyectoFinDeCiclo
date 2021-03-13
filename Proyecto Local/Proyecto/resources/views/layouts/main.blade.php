<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    @yield('scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    @yield('hojasCSS')

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

</head>
<body>
    @include('cookieConsent::index')
    <div id="app">
        <nav class="navbar navbar-expand-md {{ ($check3=="checked") ? 'navbar-dark' : 'navbar-light' }}">
            <div class="container">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse ml-2" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }} {{ (\Request::route()->getName() == 'home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Todo') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }} {{ (\Request::route()->getName() == 'images') ? 'active' : '' }}" href="{{ route('images') }}">{{ __('Imágenes') }}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }}" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }}" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }}" href="{{ route('alta') }}">{{ __('Alta de sitios') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link {{ ($check3=="checked") ? 'sombras' : '' }} dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('historial') }}">
                                        {{ __('Historial') }}
                                    </a>
                                    @if(Auth::user()->id==1)
                                    <a class="dropdown-item" href="{{ route('admin') }}">
                                        {{ __('Panel admin.') }}
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('settings') }}">
                                        {{ __('Configuración') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div id="carousel" class="carousel slide" data-ride="carousel" {{ ($check3=="checked") ? '' : 'style=display:none' }}>

                <ul class="carousel-indicators mb-5 mb-lg-3">
                    @for($i=0;$i<strlen($fondos_activos);$i++)
                        @if($i==0)
                            <li data-target="#carousel" data-slide-to="{{$i}}" class="active"></li>
                        @else
                            <li data-target="#carousel" data-slide-to="{{$i}}"></li>
                        @endif
                    @endfor
                </ul>

                <!-- The slideshow -->
                <div class="carousel-inner">
                    @for($i=0;$i<strlen($fondos_activos);$i++)
                        @if($i==0)
                            <div class="carousel-item active">
                                @else
                                    <div class="carousel-item">
                                        @endif
                                        <div class="d-flex align-items-center justify-content-center min-vh-100">
                                            @if(substr($fondos_activos,$i,1)<4)
                                                <img src="{{ URL::to('/') }}/images/fondo{{substr($fondos_activos,$i,1)}}.jpg">
                                            @else
                                                <img src="{{ URL::to('/') }}/imagen/{{substr($fondos_activos,$i,1)-3}}">
                                            @endif
                                        </div>
                                    </div>
                                    @endfor
                            </div>

                </div>
            </div>
            @yield('content')
            <div id="footer" class="ml-md-3 ml-0 mb-3 px-md-3 p-0 col-md-auto col-12">
              <ul class="nav nav d-flex justify-content-center">
                <li class="nav-item mx-2">
                  <a href="{{route('cookies')}}" class="{{ ($check3=="checked") ? 'sombras text-light' : 'text-dark' }}">Política de cookies</a>
                </li>
                <li class="nav-item mx-2">
                  <a href="{{route('terminos')}}" class="{{ ($check3=="checked") ? 'sombras text-light' : 'text-dark' }}">Condiciones</a>
                </li>
                <li class="nav-item mx-2">
                  <a href="{{route('acercade')}}" class="{{ ($check3=="checked") ? 'sombras text-light' : 'text-dark' }}">Acerca de</a>
                </li>
              </ul>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
