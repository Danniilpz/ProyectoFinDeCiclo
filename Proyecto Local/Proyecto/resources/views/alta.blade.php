@extends('layouts.secondary')

@section('pageTitle','Alta de sitios - Loopz')

@section('hojasCSS')
	<link rel="stylesheet" href="css/forms.css"/>
@endsection

@section('scripts')
	<script src="js/forms.js"></script>
@endsection

@section('content')
	<div class="container">
		<h2>Alta de sitios web en Loopz</h2>
		<p>El robot de Loopz rastrea cada URL del registro, guarda cada palabra encontrada y vuelve a rastrear cada enlace encontrado en un proceso recursivo.</br>
			De esta forma, todas las páginas web de la red son en algún momento rastreadas. Sin embargo, si una URL específica no aparece frecuentemente en
			otras páginas web, el proceso de encontrarla y, por lo tanto, rastrear ese sitio, puede llevar más tiempo. </br>
			Con este formulario, puedes hacer que el robot de Loopz acceda directamente a una URL antes de encontrarla en la red.
			Solamente debes introducir una URL cualquiera por cada sitio web, ya que el robot accede por medio de la URL principal.</p>
		<hr/>
		@isset($success)
			<div class="alert alert-success">{{$success}}</div>
		@endisset
		@isset($error)
			<div class="alert alert-danger">{{$error}}</div>
		@endisset
		<div class="form-group">
			<form method="post" class="form-inline" action="alta">
				{{ csrf_field() }}
				<div class="input-group col-12">
					<label class="mr-2 col-md-auto col-12 d-flex justify-content-start" for="newname">Introduce una URL válida</label>
					<input class="form-control" type="url" id="campo" placeholder="http://www.dominio.com/" name="direccion"/>
					<div class="input-group-append">
						<input class="btn btn-primary" type="submit" value="Dar de alta"/>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
