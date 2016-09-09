@extends('panelgeneral2')

@section('titulopanel2')
<h3>Verificar materias sin profesor</h3>
@stop

@section('cuerpopanel2')
<p>A continuación se muestran las materias que no tienen un profesor asignado y las que tienen a uno asignado.
</p>
<div class="panel-group" id="accordion">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
    					<h3 class="panel-title">Materias sin profesor</h3>
  					</div>
					<div class="panel-body">
						<ul class="list-group">
							@if(isset($materiaInNivelSinProfe) and $materiaInNivelSinProfe)
							@if($materiaInNivelSinProfe->count()>0)
							@foreach($materiaInNivelSinProfe as $campo)
    							<li class="list-group-item">@if(is_object($campo->niveles))<em>{{$campo->niveles->nombre_nivel}}</em>. {{$campo->materias->nombre_materia}}@endif</li>
    						@endforeach
    						@endif
   							@endif 
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
    					<h3 class="panel-title">Materias con profesor</h3>
  					</div>
					<div class="panel-body">
						<ul class="list-group">
							@if(isset($materiaInNivelConProfe) and $materiaInNivelConProfe)
							@if($materiaInNivelConProfe->count()>0)
							@foreach($materiaInNivelConProfe as $campo)
    							<li class="list-group-item">@if(is_object($campo->niveles))<em>{{$campo->niveles->nombre_nivel}}, {{$campo->materias->nombre_materia}}</em>. {{$campo->empleados->users->name}} {{$campo->empleados->users->lastname}}. @endif<form method="POST" action="{{ route('editar_profesor')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <input type="hidden" name="accion" value="1">
                                <button class="btn btn-danger btn-xs">Borrar profesor</button>
                            </form></li>
    						@endforeach
    						@endif
   							@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop