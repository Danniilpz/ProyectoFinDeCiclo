@extends('layouts.search')

@section('pageTitle',$search.' - Buscar con Loopz')

@section('hojasCSS')
	<link rel="stylesheet" href="{{URL::to("/")}}/css/results.css"/>
@endsection

@section('scripts')
	<script src="{{URL::to("/")}}/js/autocomplete.js"></script>
	<script src="{{URL::to("/")}}/js/resultados.js"></script>
@endsection


@section('content')
	<div class="container pl-5">
		@if($numres>0)
			<div class="alert text-success alert-results">Se han encontrado {{$numres}} resultados</div>
			<div class="resultados row mb-3">
			@foreach($resultados as $resultado)
				{!!$resultado!!}
			@endforeach
			</div>
			<div class="clear"></div>
			@include('paginacion')
		@else
			<span id='results' class="text-danger">No se han encontrado resultados de búsqueda</span>
		@endif
	</div>
@endsection
