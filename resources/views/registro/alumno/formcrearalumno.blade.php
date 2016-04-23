@extends('formgeneral')

@section('formheader')
id="crearalumno" method="POST" action="{{ route('crear_alumno') }}" 
@stop

@section('forminterior')
<fieldset>
	<legend>Datos de ingreso: </legend>
	<div class="form-group">
		<label class="control-label" for="nombre_alumno">Usuario</label> 
		{{-- <input id='buscarAlumno' type='text' placeholder='Digite su búsqueda'> Trabajando aca --}}
		<div id='suggestions'></div>
	    <select id="alumno_seleccionado" class="form-control" name="users_id">
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
		<label class="control-label" for="nombre_alumno">Nivel</label>  
	    <select id="alumno_seleccionado" class="form-control" name="niveles_id">
	    	@if(isset($nivel) && $nivel)
	    	@if($nivel->count()>0)
	    		@foreach($nivel as $temporal)
	    			<option value="{{$temporal->id}}">{{$temporal->nombre_nivel}}</option>
	    		@endforeach
	    	@endif
	    	@endif
	    </select>
	</div>
	<div class="form-group">
		<label class="control-label" for="pension">Pensión mensual asignada</label>
		<input id="pension" name="pension" type="number" step="1" class="form-control">  
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion_pen">Descripcion de la pensión</label>
		<div class="col-sm-9">  
	    	<textarea id="descripcion_pen" name="descripcion_pen" cols="3" form="crearalumno" class="form-control"></textarea>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="matricula">Matrícula del nivel</label>
		<input id="matricula" name="matricula" type="number" step="1" class="form-control">  
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="descripcion_mat">Descripcion de la matrícula</label>
		<div class="col-sm-9">  
	    	<textarea id="descripcion_mat" name="descripcion_mat" cols="3" form="crearalumno" class="form-control"></textarea>
	    </div>
	</div>
	{!! Form::submit('Crear alumno',['class' => 'btn btn-primary']) !!}
</fieldset>
<script type="text/javascript">
	var autocompletar=new Array();
	$(#buscarAlumno).change(function(
		alert('Tienes el valor');
		));
</script>
@stop