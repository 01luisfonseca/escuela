@extends('formgeneral')

@section('formheader')
id="editarempleado" method="POST" action="{{ route('editar_empleado') }}" 
@stop

@section('forminterior')
<fieldset>

	<legend>Datos de modificación: </legend>

	@if( isset($accion) && $accion )
	<input id="eliminar" name="eliminar" type="hidden" value="1">
	@else
	<input id="modificar" name="modificar" type="hidden" value="1">
	@endif
	<input id="id" name="id" type="hidden" value="{{ $result->id }}">
	<input id="id" name="users_id" type="hidden" value="{{ $result->users_id }}">
	<div class="form-group">
		<label class="control-label" for="user_id">Nombre de empleado</label>  
		<select id="empleado_seleccionado" class="form-control" disabled>
	    		<option value="">Nombre: {{$result->users->name}} {{$result->users->lastname}} -- Identificación: {{$result->users->identificacion}}</option>
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="salario">Salario base</label>  
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="salario" name="salario" type="number" value="{{ $result->salario }}" step="0.01" class="form-control input-md" >
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="eps">Nombre de EPS</label>  
	    <input id="eps" name="eps" type="text" class="form-control input-md" value="{{ $result->eps }}" >
	</div>
	<div class="form-group">
		<label class="control-label" for="eps_val">Porcentaje legal de EPS</label> 
		<div class="input-group">
			<input id="eps_val" name="eps_val" type="number" class="form-control input-md" step="0.01" value="{{ $result->eps_val }}" >
			<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="arl">Nombre de ARL</label>  
	    <input id="arl" name="arl" type="text" class="form-control input-md" value="{{ $result->arl }}" >
	</div>
	<div class="form-group">
		<label class="control-label" for="arl_val">Porcentaje legal de ARL</label>  
		<div class="input-group">
			<input id="arl_val" name="arl_val" type="number" class="form-control input-md" step="0.01" value="{{ $result->arl_val }}" >
			<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="pension">Nombre de gestión pensión</label>  
	    <input id="pension" name="pension" type="text" class="form-control input-md" value="{{ $result->pension }}" >
	</div>
	<div class="form-group">
		<label class="control-label" for="pension_val">Porcentaje legal de pensión</label>
		<div class="input-group">  
	    	<input id="pension_val" name="pension_val" type="number" class="form-control input-md" step="0.01" value="{{ $result->pension_val }}" >
	    	<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente el nivel',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Modificar nivel',['class' => 'btn btn-warning']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('registro') }}">Cancelar y volver</a>
</fieldset>
@stop