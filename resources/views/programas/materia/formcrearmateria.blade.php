@extends('formgeneral')

@section('formheader')
id="crearmateria" method="POST" action="{{ route('crear_materia') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	<div class="form-group">
		<label class="control-label" for="nombre_materia">Nombre del materia</label>  
	    <input id="nombre_materia" name="nombre_materia" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion del materia</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="crearmateria" class="form-control input-md"></textarea>
	</div>
	{!! Form::submit('Crear materia',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop