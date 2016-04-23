@extends('panelgeneral')

@section('titulopanel')
<h3>Crear plan de estudios</h3>
@stop

@section('cuerpopanel')
<p>El plan de estudios es la agrupación del nivel, las materias y los periodos por materia. Es la agrupación de conocimeinto
	que los estudiantes desean conseguir. Así mismo, permite la gestión del estudiante, las notas y la asistencia.
</p>

<div class="panel-group" id="accordion">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Seleccione el nivel del plan de estudios: </legend>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="1">
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
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Nivel Seleccionado: </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							<div class="form-group">
								<label class="col-sm-12 control-label">Nivel</label>
								<div class="col-sm-12">
      								<p class="form-control-static">{{ $nivel_respuesta->nombre_nivel }}</p>
    							</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 control-label">Descripción</label>
								<div class="col-sm-12">
      								<p class="form-control-static">{{ $nivel_respuesta->descripcion }}</p>
    							</div>
							</div>
							@endif
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		@if(isset($nivel_seleccionado))
		<br>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Seleccione la materia a incluir al plan de estudios: </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_materia">Seleccione materia</label>  
	    						<select id="materia_seleccionado" class="form-control input-sd" name="materia_id">
	    							@if($materia->count()>0)
	    							@foreach($materia as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->nombre_materia}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar materia',['class' => 'btn btn-primary']) !!}
							@endif							
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Materias incluidas al plan de estudios, seleccione la que desea quitar (También eliminará los periodos asociados): </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_materia">Seleccione materia</label>  
	    						<select id="materiainnivel_seleccionado" class="form-control input-sd" name="materiainnivelerase_id">
	    							@if($materiaInNivel->count()>0)
	    							@foreach($materiaInNivel as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->materias->nombre_materia}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Borrar materia del nivel',['class' => 'btn btn-primary']) !!}
							@endif							
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Seleccione el periodo a incluir a la materia del nivel: </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							<div class="form-group">
								<select id="materiainnivel_seleccionado" class="form-control input-sd" name="materiainnivel_id">
	    							@if($materiaInNivel->count()>0)
	    							@foreach($materiaInNivel as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->materias->nombre_materia}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_periodo">Seleccione periodo</label>  
	    						<select id="periodo_seleccionado" class="form-control input-sd" name="periodo_id">
	    							@if($periodo->count()>0)
	    							@foreach($periodo as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->nombre_periodo}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Seleccionar periodo',['class' => 'btn btn-primary']) !!}
							@endif
							
						</fieldset>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body">
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Seleccione la materia del nivel, asociada al periodo: </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
							<div class="form-group">
								<select id="{{isset($materiainnivel_seleccionado)? 'disabledSelect' : 'materiainnivel_seleccionado'}}" class="form-control input-sd" name="materiainnivel_seleccionado">
	    							@if(isset($materiainnivel_seleccionado))
	    							<option>Seleccionar el periodo a borrar</option>
	    							@else
	    							@if($materiaInNivel->count()>0)
	    							@foreach($materiaInNivel as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->materias->nombre_materia}}</option>
	    							@endforeach
	    							@endif
	    							@endif
	    						</select>
							</div>
							@if(!isset($materiainnivel_seleccionado))
							{!! Form::submit('Seleccionar Materia del nivel',['class' => 'btn btn-primary']) !!}
							@endif
							@endif
						</fieldset>
					{!! Form::close() !!}
					{!! Form::open(['route'=>'crear_plan', 'method'=>'POST', 'class'=>'form']) !!}
						<fieldset>
						<legend>Seleccione el periodo a eliminar: </legend>
							@if(isset($nivel_seleccionado) && $nivel_seleccionado)
							@if(isset($materiainnivel_seleccionado) && $materiainnivel_seleccionado)
							<div class="form-group">
								<input type="hidden" name="nivel_seleccionado" value="{{$nivel_seleccionado}}">
								<label class="control-label" for="nombre_periodo">Seleccione periodo</label>  
	    						<select id="periodo_seleccionado" class="form-control input-sd" name="periodoeliminar_id">
	    							@if($periodoinmateria->count()>0)
	    							@foreach($periodoinmateria as $temporal)
	    							<option value="{{$temporal->id}}">{{$temporal->periodos->nombre_periodo}}</option>
	    							@endforeach
	    							@endif
	    						</select>
							</div>
							{!! Form::submit('Borrar periodo',['class' => 'btn btn-primary']) !!}
							@endif
							@endif							
						</fieldset>
					{!! Form::close() !!}
				</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

@stop