@extends('panelgeneral')

@section('titulopanel')
<h3>Asignar profesor a materia</h3>
@stop

@section('cuerpopanel')
<div class="panel-group" id="accordion">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_profesor', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Seleccione el nivel: </legend>
							<div class="form-group">
								<label class="control-label" for="nombre_nivel">Seleccione nivel</label>  
	    						<select id="nivel_seleccionado" class="form-control" name="nivel_id">
	    							@if($nivel->count()>0)
	    							@foreach($nivel as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->nombre_nivel}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar nivel',['class' => 'btn btn-primary']) !!}
						</fieldset>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				@if(isset($nivel_seleccionado) && $nivel_seleccionado)
				<div class="panel panel-default">
					<div class="panel-body">						
						{!! Form::open(['route'=>'crear_profesor', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Nivel Seleccionado: </legend>
							<div class="form-group">
								<div class="col-sm-12">
      								<p class="form-control-static">{{ $nivel_respuesta->nombre_nivel }}</p>
    							</div>
							</div>
						</fieldset>
						{!! Form::close() !!}
					</div>
				</div>
				@endif
			</div>
		</div>
		@if(!is_null($nivel_seleccionado))
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_profesor', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Seleccionar materia: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_materia">Seleccione materia</label>  
	    						<select id="materiainnivel_seleccionado" class="form-control" name="materiainnivel_id">
	    							@if($materiaInNivel->count()>0)
	    							@foreach($materiaInNivel as $temporal)
	    							<option value="{{$temporal->id}}">@if(is_object($temporal->materias)){{$temporal->materias->nombre_materia}}@endif</option>
	    							@endforeach
	    							@else
	    							<option value="">No hay materias en nivel. Ajuste el plan de estudios, en programas educativos</option>
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar materia',['class' => 'btn btn-primary']) !!}						
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				@if(!is_null($materiainnivel_seleccionado) && $materiainnivel_seleccionado)
				<div class="panel panel-default">
					<div class="panel-body">						
						{!! Form::open(['route'=>'crear_profesor', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Materia Seleccionada: </legend>							
							<div class="form-group">
								<div class="col-sm-12">
      								<p class="form-control-static">@if(is_object($materiaInNivelId->materias)){{ $materiaInNivelId->materias->nombre_materia }}@endif</p>
    							</div>
							</div>
						</fieldset>
						{!! Form::close() !!}						
					</div>
				</div>
				@endif
			</div>
		</div>
		@if(!is_null($materiainnivel_seleccionado) && $materiainnivel_seleccionado)
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_profesor', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Seleccionar empleado como profesor: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<input type="hidden" name="materiainnivel_seleccionado" value="{{$materiainnivel_seleccionado}}">
								<label class="control-label" for="nombre_materia">Seleccione empleado</label>  
	    						<select id="empleados" class="form-control" name="empleados_id">
	    							@if($empleado->count()>0)
	    							@foreach($empleado as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->users->name}} {{$temporal->users->lastname}}</option>
	    							@endforeach
	    							@else
	    							<option value="">No hay empleados. Ingrese empleados en administración institucional</option>
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar materia',['class' => 'btn btn-primary']) !!}							
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		@endif
		@endif
	</div>
</div>

@stop