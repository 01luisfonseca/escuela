@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar empleado</h3>
@stop

@section('cuerpopanel2')
<p>Busque el nombre o palabras clave para el empleado. 
	Se extenderá una tabla donde podrá elegir la acción y el elemento donde desea ejecutarlo.
</p>
@include('institucion/empleado/formeditarempleado')

@if (isset($respuesta) && $respuesta)
<br>
	@include('institucion/empleado/tablaeditarempleado')

@endif

@stop