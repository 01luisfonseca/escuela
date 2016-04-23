@extends('formgeneral')

@section('formheader')
id="crearnomina" method="POST" action="{{ route('crear_nomina') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	@if(!isset($calculado))
	<div class="form-group">
		<label class="control-label" for="nombre_empleado">Seleccione empleado</label>  
	    <select id="empleado_seleccionado" class="form-control" name="empleados_id">
	    	@if(isset($principal) && $principal)
	    	@if($principal->count()>0)
	    		@foreach($principal as $temporal)
	    			<option value="{{$temporal->id}}">Nombre: {{$temporal->users->name}} {{$temporal->users->lastname}} -- Identificación: {{$temporal->users->identificacion}}</option>
	    		@endforeach
	    	@endif
	    	@endif
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_ini">Fecha de inicio</label>  
	    <input id="fecha_ini" name="fecha_ini" type="date" class="form-control">
	</div>
	<div class="form-group">
		<label class="control-label" for="fecha_fin">Fecha de Final</label>  
	    <input id="fecha_fin" name="fecha_fin" type="date" class="form-control">
	</div>
	<div class="form-group">
		<label class="control-label" for="dias">Días trabajados</label>  
		<div class="input-group">
			<input id="dias" name="dias" type="number" step="0.01" class="form-control">
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
	    	@endif
	    	@endif
	    </select>
	</div>
	{!! Form::submit('Calcular liquidación',['class' => 'btn btn-primary']) !!}
	@else
	<input type="hidden" name="calculado" value="{{ $calculado }}">
	<input type="hidden" name="fecha_ini" value="{{ $fecha_ini }}">
	<input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
	<input type="hidden" name="dias" value="{{ $dias }}">
	<input type="hidden" name="mes_id" value="{{ $mes->id }}">
	<input type="hidden" name="empl_selec_id" value="{{ $empl_selec->id }}">
	<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-6" for="salario">Salario calculado para {{ $empl_selec->users->name }} {{ $empl_selec->users->lastname }}, para el mes de {{ $mes->nombre_mes }}</label>  
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario_base">Salario base</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="salario_base" name="salario_base" type="number" value="{{isset($empl_selec->salario)? $empl_selec->salario : 0}}" step="0.01" class="form-control" disabled>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Salario calculado para el mes</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="salario_pagado" name="salario_pagado" type="number" value="{{ isset($sal_trab)? round($sal_trab) : 0 }}" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Auxilio de movilización</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="auxmovil" name="auxmovil" type="number" value="{{ isset($auxmovil)? round($auxmovil) : 0 }}" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">EPS a cargo del empleado</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="eps_empleado" name="eps_empleado" type="number" value="{{ isset($sal_trab)? round($sal_trab*$porc_emp_eps) : 0 }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">EPS a cargo del empleador</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="eps_empresa" name="eps_empresa" type="number" value="{{ isset($sal_trab)? round($sal_trab*(($empl_selec->eps_val/100)-0.04)) : 0 }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Pensión a cargo del empleado</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="pension_empleado" name="pension_empleado" type="number" value="{{ isset($sal_trab)? round($sal_trab*$porc_emp_pen) : 0 }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Pensión a cargo del empleador</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="pension_empresa" name="pension_empresa" type="number" value="{{ isset($sal_trab)? round($sal_trab*(($empl_selec->pension_val/100)-0.04)) : 0 }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">ARL</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="arl_empresa" name="arl_empresa" type="number" value="{{ isset($sal_trab)? round($sal_trab * $empl_selec->arl_val/100) : 0 }}" step="1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Descuentos</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="descuento" name="descuento" type="number" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Descripcion del descuento</label>
		<div class="col-sm-7">  
	    	<textarea id="descripcion_desc" name="descripcion_desc" cols="3" form="crearnomina" class="form-control"></textarea>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="salario">Bonificaciones</label>  
		<div class="input-group col-sm-7">
			<span class="input-group-addon" id="basic-addon1">$</span>
	    	<input id="bonificacion" name="bonificacion" type="number" step="0.01" class="form-control">
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Descripcion de bonificaciones</label>
		<div class="col-sm-7">  
	    	<textarea id="descripcion_boni" name="descripcion_boni" cols="3" form="crearnomina" class="form-control"></textarea>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion">Notas generales de liquidación</label>
		<div class="col-sm-7">  
	    	<textarea id="notas" name="notas" cols="3" form="crearnomina" class="form-control"></textarea>
	    </div>
	</div>
	</div>
	{!! Form::submit('Liquidar nómina',['class' => 'btn btn-primary']) !!}
	@endif
</fieldset>
@stop