@extends('panelgeneral')

@section('titulopanel')
<h3>Crear alumno - Asignar usuario a nivel</h3>
@stop

@section('cuerpopanel')
<p>El alumno es un usuario al cual se le asigna un nivel. Un alumno puede tener varios niveles.
</p>
<p>Estos son los ultimos 10 alumnos creados o modificados:
</p>
<ul class="list-group">
	@if(isset($actuales) and $actuales)
	@if($actuales->count()>0)
	@foreach($actuales as $campo)
    <li class="list-group-item"><em>{{$campo->users->name}} {{$campo->users->lastname}}, {{$campo->niveles->nombre_nivel}}</em>.  Actualizado en {{$campo->updated_at}}</li>
    @endforeach
    @endif
    @endif
</ul>
<br>
@include('registro/alumno/formcrearalumno')
@stop