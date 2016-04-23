@extends('panelgeneral')

@section('titulopanel')
<h3>Asignar calificación a alumno por materia</h3>
@stop

@section('cuerpopanel')
<div class="panel-group" id="accordion">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form-inline']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form']) !!}
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
						{!! Form::open(['route'=>'crear_rendimiento', 'method'=>'POST', 'class'=>'form','id'=>'crearnota']) !!}
						<fieldset>
						<legend>Ingresar nota a alumnos: </legend>
							<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
							<input type="hidden" name="materiainnivel_seleccionado" value="{{$materiainnivel_seleccionado}}">
							<input type="hidden" name="periodoinmateria_seleccionado" value="{{$periodoinmateria_seleccionado}}">
							<div class="form-group">
								<label class="control-label" for="nombre_registro">Nombre de nota</label>
								<input id="nombre_nota" class="form-control" type="text" name="nombre_nota" required>
							</div>
							<div class="form-group">
								<label class="control-label" for="descripcion">Descripcion</label>  
	   							<textarea id="descripcion" name="descripcion" cols="3" form="crearnota" class="form-control input-md"></textarea>
							</div>
							<br>
							@if($alumnosInNivel->count()>0)
	    					@foreach($alumnosInNivel as $temporal)
							<div class="form-group">
								<label class="control-label" for="nombre_registro">Alumno: {{$temporal->users->name}} {{$temporal->users->lastname}}</label>
								<input id="rendimientocode" class="form-control" type="number" step="0.01" value="0" name="calificacion[{{$temporal->id}}]">
							</div>
							@endforeach
							@else
							<div class="form-group">
								<label class="control-label" for="nombre_registro">No hay Alumnos para el nivel. Ajuste Alumnos, en programas educativos</label>
							</div>
							@endif					
						</fieldset>
						@if($alumnosInNivel->count()>0)
						{!! Form::submit('Guardar Calificación',['class' => 'btn btn-primary']) !!}
						@endif
						{!! Form::close() !!}
						<br>
						<h5>{{ isset($mensajefinal)? $mensajefinal : ''}}</h5>
						<br>
						<p>Estos son las ultimas 100 notas de la materia y usuario:</p>
						<ul class="list-group">
							@if(isset($actuales) and $actuales)
								@if($actuales->count()>0)
									@foreach($actuales as $campo)
    									<li class="list-group-item"><em>{{$campo->alumnos->users->name}} {{$campo->alumnos->users->lastname}}</em>.Nota: {{$campo->nombre_nota}}, {{$campo->calificacion}}. Actualizado: {{$campo->updated_at}}</li>
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

@stop