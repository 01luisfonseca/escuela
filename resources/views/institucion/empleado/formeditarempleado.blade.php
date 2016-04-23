@extends('formgeneral2')

@section('formheader2')
id="editarnivel" method="POST" action"{{ route('crear_empleado') }}" 
@stop

@section('forminterior2')
<fieldset>
	<legend>Busqueda de palabras clave: </legend>
	<div class="form-group">
		<label class="control-label" for="busqueda">Texto a buscar</label>  
	    <input id="busqueda" name="busqueda" type="text" class="form-control input-md">
	</div>
	{!! Form::submit('Buscar',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop