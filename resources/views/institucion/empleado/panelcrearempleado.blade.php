@extends('panelgeneral')

@section('titulopanel')
<h3>Crear empleado</h3>
@stop

@section('cuerpopanel')
<p>Se pueden crear los empleados desde los usuarios existentes. Como empleados, se exige que cada uno de los campos sean 
	llenados.
</p>
<p>Estos son los ultimos 10 empleados creados o modificados:
</p>
<ul class="list-group">
	@if(isset($actuales) and $actuales)
	@if($actuales->count()>0)
	@foreach($actuales as $campo)
    <li class="list-group-item"><em>{{$campo->users->name}} {{$campo->users->lastname}}</em>.  Actualizado en {{$campo->updated_at}}</li>
    @endforeach
    @endif
    @endif
</ul>
<br>
@include('institucion/empleado/formcrearempleado')
@stop