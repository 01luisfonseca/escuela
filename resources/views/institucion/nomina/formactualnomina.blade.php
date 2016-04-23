@extends('formgeneral')

@section('formheader')
id="editarnomina" method="POST" action"{{ route('editar_nomina') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	@if( isset($accion) && $accion )
	<input id="eliminar" name="eliminar" type="hidden" value="1">
	@endif
	<input type="hidden" name="id" value="{{ $result->id }}">
	<div class="form-group">
		<label class="control-label" for="nombre_empleado">Empleado: {{ $result->empleados->users->name }} {{ $result->empleados->users->lastname }}</label>  
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_ini">Fecha de inicio</label>  
	    <input id="fecha_ini" name="fecha_ini" type="date" value="{{ $result->fecha_ini }}" class="form-control">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_fin">Fecha de Final</label>  
	    <input id="fecha_fin" name="fecha_fin" type="date" value="{{ $result->fecha_fin }}" class="form-control">
	</div>
	<div class="form-group">
		<label class="control-label" for="dias">Días trabajados</label>  
		<div class="input-group">
			<input id="dias" name="dias" type="number" step="0.01" value="{{ $result->dias }}" class="form-control">
			<span class="input-group-addon" id="basic-addon1"> días </span>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="mes">Mes</label>
	    <select id="mes_id" class="form-control" name="mes_id">	
	    	@if(isset($meses) && $meses)
	    	@if($meses->count()>0)
	    		@foreach($meses as $temporal)
	    			<option value="{{$temporal->id}}">{{$temporal->nombre_mes}}</option>
	    		@endforeach
	    			<option value="{{ $result->mes_id }}" selected>Opcion guardada</option>
	    	@endif
	    	@endif
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario_base">Salario base</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="salario_base" name="salario_base" type="number" value="{{ $result->salario_pagado }}" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Auxilio de movilización</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="auxmovil" name="auxmovil" type="number" value="{{ $result->auxmovil }}" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">EPS a cargo del empleado</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="eps_empleado" name="eps_empleado" type="number" value="{{ $result->eps_empleado }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">EPS a cargo del empleador</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="eps_empresa" name="eps_empresa" type="number" value="{{ $result->eps_empresa }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Pensión a cargo del empleado</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="pension_empleado" name="pension_empleado" type="number" value="{{ $result->pension_empleado }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Pensión a cargo del empleador</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="pension_empresa" name="pension_empresa" type="number" value="{{ $result->pension_empresa }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">ARL</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="arl_empresa" name="arl_empresa" type="number" value="{{ $result->arl_empresa }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Descuentos</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="descuento" name="descuento" type="number" value="{{ $result->descuento }}" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Descripcion del descuento</label>
		<div class="col-sm-7">  
	    	<textarea id="descripcion_desc" name="descripcion_desc" cols="3" form="editarnomina" value="{{ $result->descripcion_desc }}" class="form-control"></textarea>
	    </div>
	    <br>
	    <br>
	    <br>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Bonificaciones</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="bonificacion" name="bonificacion" type="number" step="0.01" value="{{ $result->bonificacion }}" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Descripcion de bonificaciones</label>
		<div class="col-sm-7">  
	    	<textarea id="descripcion_boni" name="descripcion_boni" cols="3" form="editarnomina" value="{{ $result->descripcion_boni }}" class="form-control"></textarea>
	    </div>
	    <br>
	    <br>
	    <br>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Notas generales de liquidación</label>
		<div class="col-sm-7">  
	    	<textarea id="notas" name="notas" cols="3" form="editarnomina" value="{{ $result->notas }}" class="form-control"></textarea>
	    </div>
	    <br>
	    <br>
	    <br>
	</div>
	<input type="hidden" name="pagado_at" value="0">

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente la liquidación',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Actualizar liquidación',['class' => 'btn btn-primary']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('institucion') }}">Cancelar y volver</a>
</fieldset>
@stop