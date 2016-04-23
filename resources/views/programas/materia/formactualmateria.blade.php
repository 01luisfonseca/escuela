@extends('formgeneral')

@section('formheader')
id="editarmateria" method="POST" action="{{ route('editar_materia') }}" 
@stop

@section('forminterior')
<fieldset>

	<legend>Datos de modificación: </legend>

	@if( isset($accion) && $accion )
	<input id="eliminar" name="eliminar" type="hidden" value="1">
	@endif
	
	<input id="id" name="id" type="hidden" value="{{ $result->id }}">
	<div class="form-group">
		<label class="control-label" for="nombre_materia">Nombre de la materia</label>  
	    <input id="nombre_materia" name="nombre_materia" value="{{ $result->nombre_materia }}" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion de la materia</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="editarmateria" class="form-control input-md">{{ $result->descripcion }}</textarea>
	</div>

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente la materia',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Modificar materia',['class' => 'btn btn-warning']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('programas') }}">Cancelar y volver</a>
</fieldset>
@stop