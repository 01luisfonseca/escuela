@extends('formgeneral')

@section('formheader')
id="crearmatricula" method="POST" action="{{ route('crear_matricula') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Ingrese los criterios de búsqueda para asignar matrícula: </legend>
	<div class="form-group">
		<label class="control-label" for="busqueda">Nombre de alumno o nivel relacionado</label>  
	    <input id="busqueda" name="busqueda" type="text" class="form-control">
	</div>
	
	{!! Form::submit('Buscar información',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop