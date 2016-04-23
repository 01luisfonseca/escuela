@extends('panelgeneral')

@section('titulopanel')
<h3>Crear nivel o Curso</h3>
@stop

@section('cuerpopanel')
<p>En este espacio podrá crear los niveles para la institución. Los niveles son
	la agrupación de materias, del profesor asignado a cada una, de los periodos 
	para cada materia y las notas y asistencias para cada periodo. Representan el grado a aprobar. 
	Los niveles serán relacionados con los demás elementos en la sección "Crear plan de estudio por niveles"
</p>
<p>Actualmente exiten los siguientes elementos:
</p>
<ul class="list-group">
	@foreach($principal as $campo)
    <li class="list-group-item"><em>{{$campo->nombre_nivel}}</em>. {{$campo->descripcion}}</li>
    @endforeach
</ul>
<br>
@include('programas/nivel/formcrearnivel')
@stop