@extends('formgeneral')

@section('formheader')
id="crearempleado" method="POST" action"{{ route('crear_empleado') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	<div class="form-group">
		<label class="control-label" for="nombre_empleado">Usuario</label>  
	    <select id="empleado_seleccionado" class="form-control" name="user_id">
	    	@if(isset($principal) && $principal)
	    	@if($principal->count()>0)
	    		@foreach($principal as $temporal)
	    			<option value="{{$temporal->id}}">Nombre: {{$temporal->name}} {{$temporal->lastname}} -- Identificación: {{$temporal->identificacion}}</option>
	    		@endforeach
	    	@endif
	    	@endif
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="salario">Salario base</label>  
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="salario" name="salario" type="number" step="0.01" class="form-control input-md" required>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="eps">Nombre de EPS</label>  
	    <input id="eps" name="eps" type="text" class="form-control input-md" required>
	</div>
	<div class="form-group">
		<label class="control-label" for="eps_val">Porcentaje legal de EPS</label> 
		<div class="input-group">
			<input id="eps_val" name="eps_val" type="number" step="0.01" class="form-control input-md" value="16" required>
			<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="arl">Nombre de ARL</label>  
	    <input id="arl" name="arl" type="text" class="form-control input-md" required>
	</div>
	<div class="form-group">
		<label class="control-label" for="arl_val">Porcentaje de ARL</label>  
		<div class="input-group">
			<input id="arl_val" name="arl_val" type="number" step="0.01" class="form-control input-md" required>
			<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="pension">Nombre de Administradora de pensión</label>  
	    <input id="pension" name="pension" type="text" class="form-control input-md" required>
	</div>
	<div class="form-group">
		<label class="control-label" for="pension_val">Porcentaje legal de pensión</label>
		<div class="input-group">  
	    	<input id="pension_val" name="pension_val" type="number" step="0.01" value="12.5" class="form-control input-md" required>
	    	<span class="input-group-addon" id="basic-addon1">%</span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_ini">Fecha de inicio de contrato</label>  
	    <input id="contrato_ini" name="contrato_ini" type="date" class="form-control input-md" required>
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_fin">Fecha de terminación de contrato</label>  
	    <input id="contrato_fin" name="contrato_fin" type="date" class="form-control input-md">
	</div>
	{!! Form::submit('Crear empleado',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop