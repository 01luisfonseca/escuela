@extends('formgeneral')

@section('formheader')
id="crearpension" method="POST" action="{{ route('crear_pension') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Ingrese los criterios de búsqueda para asignar pensión: </legend>
	<div class="form-group">
		<label class="control-label" for="busqueda">Nombre del alumno o nivel relacionado</label>  
	    <input id="busqueda" name="busqueda" type="text" class="form-control">
	</div>
	
	{!! Form::submit('Buscar información',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop