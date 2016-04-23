@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar plan o Curso</h3>
@stop

@section('cuerpopanel2')
<p>Busque el nombre o palabras clave para el nivel. 
	Se extenderá una tabla donde podrá elegir la acción y el elemento donde desea ejecutarlo.
</p>
@include('programas/plan/formeditarplan')

@if (isset($respuesta) && $respuesta)
<br>
	@include('programas/plan/tablaeditarplan')

@endif

@stop