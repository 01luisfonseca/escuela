@extends('panelgeneral')

@section('titulopanel')
<h3>Asignar asistencia a materia</h3>
@stop

@section('cuerpopanel')
<div class="panel-group" id="accordion">
	<div ng-app='asistencia' class="container-fluid">
		<asist-dir></asist-dir>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
		@if(isset($nivel_seleccionado))
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Seleccionar materia: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_materia">Seleccione materia</label>  
	    						<select id="materiainnivel_seleccionado" class="form-control" name="materiainnivel_id">
	    							@if($materiaInNivel->count()>0)
	    							@foreach($materiaInNivel as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->materias->nombre_materia}}</option>
	    							@endforeach
	    							@else
	    						</select>
	    							<p class="form-control-static">No hay materias. Ajuste el plan de estudios, en programas educativos</p>
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
				@if(isset($materiainnivel_seleccionado) && $materiainnivel_seleccionado)
				<div class="panel panel-default">
					<div class="panel-body">						
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Materia Seleccionada: </legend>							
							<div class="form-group">
								<div class="col-sm-12">
      								<p class="form-control-static">{{ $materiaInNivelId->materias->nombre_materia }}</p>
    							</div>
							</div>
						</fieldset>
						{!! Form::close() !!}						
					</div>
				</div>
				@endif
			</div>
		</div>
		@if(isset($materiainnivel_seleccionado) && $materiainnivel_seleccionado)
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form-inline']) !!}
						<fieldset>
						<legend>Seleccionar periodo de la materia: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<input type="hidden" name="materiainnivel_seleccionado" value="{{$materiainnivel_seleccionado}}">
								<label class="control-label" for="nombre_periodo">Seleccione periodo</label>  
	    						<select id="periodoinmateria_seleccionado" class="form-control" name="periodoinmateria_id">
	    							@if($periodoInMateria->count()>0)
	    							@foreach($periodoInMateria as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->periodos->nombre_periodo}}</option>
	    							@endforeach
	    							@else
	    						</select>
	    							<p class="form-control-static">No hay periodos para materias. Ajuste el plan de estudios, en programas educativos</p>
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar periodo',['class' => 'btn btn-primary']) !!}						
						</fieldset>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				@if(isset($periodoinmateria_seleccionado) && $periodoinmateria_seleccionado)
				<div class="panel panel-default">
					<div class="panel-body">						
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Periodo seleccionado: </legend>							
							<div class="form-group">
								<div class="col-sm-12">
      								<p class="form-control-static">{{ $periodoInMateriaId->periodos->nombre_periodo }}</p>
    							</div>
							</div>
						</fieldset>
						{!! Form::close() !!}						
					</div>
				</div>
				@endif
			</div>
		</div>
		@if(isset($periodoinmateria_seleccionado) && $periodoinmateria_seleccionado)
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_asistencia', 'method'=>'POST', 'class'=>'form-inline', 'name'=>'ingresoasistencia', 'id'=>'ingresoasistencia']) !!}
						<fieldset>
						<legend>Ingresar asistencia: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<input type="hidden" name="materiainnivel_seleccionado" value="{{$materiainnivel_seleccionado}}">
								<input type="hidden" name="periodoinmateria_seleccionado" value="{{$periodoinmateria_seleccionado}}">
								<label class="control-label" for="nombre_asistencia">Lectura de código</label>  
	    						<input id="asistenciacode" class="form-control" type="text" name="asistenciacode" required>
							</div>
							{!! Form::submit('Envío manual del codigo',['class' => 'btn btn-primary']) !!}							
						</fieldset>
						{!! Form::close() !!}
						<br>
						<h5>{{ isset($mensajefinal)? $mensajefinal : ''}}</h5>
						<br>
						<p>Estos son las ultimas 30 asistencias de la materia y periodo:</p>
						<ul class="list-group">
							@if(isset($actuales) and $actuales)
								@if($actuales->count()>0)
									@foreach($actuales as $campo)
    									<li class="list-group-item"><em>{{$campo->alumnos->users->name}} {{$campo->alumnos->users->lastname}}</em>.  Actualizado en {{$campo->updated_at}}</li>
    								@endforeach
    							@endif
    						@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
		@endif
		@endif
		@endif
	</div>
</div>
<script src="{{ route('public') }}/js/registro/asistencia.js"></script>

@stop