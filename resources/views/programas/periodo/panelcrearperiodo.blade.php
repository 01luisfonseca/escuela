@extends('panelgeneral')

@section('titulopanel')
<h3>Crear periodo</h3>
@stop

@section('cuerpopanel')
<p>Los periodos son franjas de tiempo en los que se desarrolla una materia, en un nivel. Pueden habar varios periodos por materia.
	Representan la fecha de corte para notas, una definitiva, asistencia, etc.  
	Los periodos serán relacionados con los demás elementos en la sección "Crear plan de estudio por niveles"
</p>
<p>Actualmente exiten los siguientes elementos:
</p>
<ul class="list-group">
	@foreach($principal as $campo)
    <li class="list-group-item"><em>{{$campo->nombre_periodo}}</em>. {{$campo->descripcion}}</li>
    @endforeach
</ul>
<br>
@include('programas/periodo/formcrearperiodo')
@stop