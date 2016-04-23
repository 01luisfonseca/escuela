@extends('panelgeneral')

@section('titulopanel')
<h3>Busqueda de alumno para información de pensión</h3>
@stop

@section('cuerpopanel')
<p>En este lugar podrá crear, actualizar y modificar las pensiones.
</p>
<br>
@include('institucion/pension/formcrearpension')

@if(isset($respuesta) && $respuesta)
<br>
	@include('institucion/pension/tablaeditarpension')
<br>
@endif

@stop