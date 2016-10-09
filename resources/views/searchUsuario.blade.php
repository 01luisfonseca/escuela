@extends('autorizadohome')
 
@section('subcontent')

<div class="panel panel-default">
	<div class="panel-heading">Formulario de vista o modificación de usuarios</div>
	<div class="panel-body">
		<form method="POST" action="{{ route('usuarios/modificar') }}" class="form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="control-label" for="name">Criterio de Busqueda</label>  
				<input id="busqueda" name="busqueda" type="search" placeholder="Digite busqueda" class="form-control input-md" required="">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Buscar</button>
			</div>
		</form>
		<div>
		<p>Estos son los ultimos 50 usuarios creados o modificados:</p>
		<ul class="list-group">
			@foreach($principal as $campo)
			@if($campo->id!=1)
    		<li class="list-group-item"><em>{{$campo->name}} {{$campo->lastname}}</em>. Identificación: {{$campo->identificacion}}. Nivel de acceso: {{ $campo->tipo_usuario->nombre_tipo}}. Tarjeta: {{$campo->tarjeta}}. Fecha modificación: {{ $campo->updated_at }}</li>
    		@endif
    		@endforeach
		</ul>
	</div>
	</div>
</div>
@if (isset($respuesta) && $respuesta)

	@include('tablaUsers')

@endif

	@yield('subcontent')			

@endsection