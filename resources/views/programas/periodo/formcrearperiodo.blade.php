@extends('formgeneral')

@section('formheader')
id="crearperiodo" method="POST" action="{{ route('crear_periodo') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	<div class="form-group">
		<label class="control-label" for="nombre_periodo">Nombre del periodo</label>  
	    <input id="nombre_periodo" name="nombre_periodo" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_ini">Fecha de inicio</label>  
	    <input id="fecha_ini" name="fecha_ini" type="date" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_fin">Fecha de Final</label>  
	    <input id="fecha_fin" name="fecha_fin" type="date" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion del periodo</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="crearperiodo" class="form-control input-md"></textarea>
	</div>
	{!! Form::submit('Crear periodo',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop