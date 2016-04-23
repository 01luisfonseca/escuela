@extends('formgeneral')

@section('formheader')
id="editaralumno" method="POST" action="{{ route('editar_alumno') }}" 
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
		<label class="control-label" for="user_id">Nombre de alumno</label>  
		<select id="empleado_seleccionado" class="form-control" disabled>
	    		<option value="">Nombre: {{$result->users->name}} {{$result->users->lastname}} -- Identificación: {{$result->users->identificacion}}</option>
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="nombre_alumno">Nivel</label>  
	    <select id="alumno_seleccionado" class="form-control" name="niveles_id">
	    	@if(isset($nivel) && $nivel)
	    	@if($nivel->count()>0)
	    		@foreach($nivel as $temporal)
	    			<option value="{{$temporal->id}}">{{$temporal->nombre_nivel}}</option>
	    		@endforeach
	    	@endif
	    	@endif
	    	@if(isset($result->niveles->nombre_nivel))
	    		<option value="{{$result->niveles_id}}" selected>{{$result->niveles->nombre_nivel}}</option>
	    	@endif
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="pension">Pensión mensual asignada</label>
		<input id="pension" name="pension" type="number" step="1" class="form-control" value="{{$result->pension}}">  
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion_pen">Descripcion de la pensión</label>
		<div class="col-sm-9">  
	    	<textarea id="descripcion_pen" name="descripcion_pen" cols="3" form="editaralumno" class="form-control">{{$result->descripcion_pen}}</textarea>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="matricula">Matrícula del nivel</label>
		<input id="matricula" name="matricula" type="number" step="1" class="form-control" value="{{$result->matricula}}">  
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion_mat">Descripcion de la matrícula</label>
		<div class="col-sm-9">  
	    	<textarea id="descripcion_mat" name="descripcion_mat" cols="3" form="editaralumno" class="form-control">{{$result->descripcion_mat}}</textarea>
	    </div>
	</div>

	@if(isset($accion) && $accion)
	{!! Form::submit('Eliminar definitivamente',['class' => 'btn btn-danger']) !!}
	@else
	{!! Form::submit('Modificar',['class' => 'btn btn-warning']) !!}
	@endif
	<a class="btn btn-info" href="{{ route('registro') }}">Cancelar y volver</a>
</fieldset>
@stop