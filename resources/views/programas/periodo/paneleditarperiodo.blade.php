@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar periodo</h3>
@stop

@section('cuerpopanel2')
<p>Busque el nombre o palabras clave para el periodo. 
	Se extenderá una tabla donde podrá elegir la acción y el elemento donde desea ejecutarlo.
</p>
@include('programas/periodo/formeditarperiodo')

@if (isset($respuesta) && $respuesta)
<br>
	@include('programas/periodo/tablaeditarperiodo')

@endif

@stop