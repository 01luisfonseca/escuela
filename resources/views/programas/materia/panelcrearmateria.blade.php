@extends('panelgeneral')

@section('titulopanel')
<h3>Crear materia o asignatura</h3>
@stop

@section('cuerpopanel')
<p>En este espacio podrá crear las materias para todos los niveles en la institución. Las materias 
	son temas o áreas de estudio, donde habra un profesor asignado a cada una, y tendrá 
	periodos, a los cuales se les asignarán las notas y asistencias. Se deben guardar todas las materias que se van a 
	impartir en la institución.	Las materias serán relacionadas con los demás elementos en la sección "Crear plan de estudio por niveles"
</p>
<p>Actualmente exiten los siguientes elementos:
</p>
<ul class="list-group">
	@foreach($principal as $campo)
    <li class="list-group-item"><em>{{$campo->nombre_materia}}</em>. {{$campo->descripcion}}</li>
    @endforeach
</ul>
<br>
@include('programas/materia/formcrearmateria')
@stop