@extends('layouts.search')

@section('pageTitle',$search.' - Buscar con Loopz')

@section('hojasCSS')
	<link rel="stylesheet" href="css/results.css"/>
@endsection

@section('scripts')
	<script src="js/autocomplete.js"></script>
	<script src="js/buscador.js"></script>
@endsection

@section('content')
	<div class="container pl-5">
		@if($numres>0)
			<div class="alert text-success alert-results">Se han encontrado {{$numres}} resultados</div>
			<ul class="list-group">
			@foreach($resultados as $resultado)
				{!!$resultado!!}
			@endforeach
			</ul>
			@include('paginacion')
		@else
			<span id='results' class="text-danger">No se han encontrado resultados de búsqueda</span>
		@endif
	</div>
@endsection
