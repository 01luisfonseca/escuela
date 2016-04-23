@extends('formgeneral')

@section('formheader')
id="editarperiodo" method="POST" action="{{ route('editar_periodo') }}" 
@stop

@section('forminterior')
<fieldset>

	<legend>Datos de modificación: </legend>

	@if( isset($accion) && $accion )
	<input id="eliminar" name="eliminar" type="hidden" value="1">
	@endif
	
	<input id="id" name="id" type="hidden" value="{{ $result->id }}">
	<div class="form-group">
		<label class="control-label" for="nombre_periodo">Nombre del periodo</label>  
	    <input id="nombre_periodo" name="nombre_periodo" value="{{ $result->nombre_periodo }}" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_ini">Fecha de inicio</label>  
	    <input id="fecha_ini" name="fecha_ini" value="{{ $result->fecha_ini }}" type="date" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_fin">Fecha de Final</label>  
	    <input id="fecha_fin" name="fecha_fin" value="{{ $result->fecha_fin }}" type="date" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion del periodo</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="editarperiodo" class="form-control input-md">{{ $result->descripcion }}</textarea>
	</div>

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente el periodo',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Modificar periodo',['class' => 'btn btn-warning']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('programas') }}">Cancelar y volver</a>
</fieldset>
@stop