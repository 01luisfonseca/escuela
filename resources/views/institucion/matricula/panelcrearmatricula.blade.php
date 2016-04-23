@extends('panelgeneral')

@section('titulopanel')
<h3>Busqueda de alumno para información de matrícula</h3>
@stop

@section('cuerpopanel')
<p>En este lugar podrá crear, actualizar y modificar las matrículas.
</p>
<br>
@include('institucion/matricula/formcrearmatricula')

@if(isset($respuesta) && $respuesta)
<br>
	@include('institucion/matricula/tablaeditarmatricula')
<br>
@endif

@stop