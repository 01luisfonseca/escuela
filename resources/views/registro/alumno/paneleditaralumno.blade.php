@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar alumno</h3>
@stop

@section('cuerpopanel2')
<p>Busque el nombre o palabras clave para el alumno. 
	Se extenderá una tabla donde podrá elegir la acción y el elemento donde desea ejecutarlo.
</p>
@include('registro/alumno/formeditaralumno')

@if (isset($respuesta) && $respuesta)
<br>
	@include('registro/alumno/tablaeditaralumno')

@endif

@stop