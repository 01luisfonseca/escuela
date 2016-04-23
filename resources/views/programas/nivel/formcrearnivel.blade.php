@extends('formgeneral')

@section('formheader')
id="crearnivel" method="POST" action="{{ route('crear_nivel') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	<div class="form-group">
		<label class="control-label" for="nombre_nivel">Nombre del nivel</label>  
	    <input id="nombre_nivel" name="nombre_nivel" type="text" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="descripcion">Descripcion del nivel</label>  
	    <textarea id="descripcion" name="descripcion" cols="3" form="crearnivel" class="form-control input-md"></textarea>
	</div>
	{!! Form::submit('Crear nivel',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop