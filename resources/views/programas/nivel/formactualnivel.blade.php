@extends('formgeneral')

@section('formheader')
id="editarnivel" method="POST" action="{{ route('editar_nivel') }}" 
@stop

@section('forminterior')
<fieldset>

	<legend>Datos de modificación: </legend>

	@if( isset($accion) && $accion )
	<input id="eliminar" name="eliminar" type="hidden" value="1">
	@endif
	
	<input id="id" name="id" type="hidden" value="{{ $result->id }}">
	<div class="form-group">
		<label class="control-label" for="nombre_nivel">Nombre del nivel</label>  
	    <input id="nombre_nivel" name="nombre_nivel" value="{{ $result->nombre_nivel }}" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion del nivel</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="editarnivel" class="form-control input-md">{{ $result->descripcion }}</textarea>
	</div>

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente el nivel',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Modificar nivel',['class' => 'btn btn-warning']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('programas') }}">Cancelar y volver</a>
</fieldset>
@stop