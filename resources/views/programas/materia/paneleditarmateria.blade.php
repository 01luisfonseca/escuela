@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar materia</h3>
@stop

@section('cuerpopanel2')
<p>Busque el nombre o palabras clave para la materia. 
	Se extenderá una tabla donde podrá elegir la acción y el elemento donde desea ejecutarlo.
</p>
@include('programas/materia/formeditarmateria')

@if (isset($respuesta) && $respuesta)
<br>
	@include('programas/materia/tablaeditarmateria')

@endif

@stop