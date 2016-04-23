@extends('panelgeneral2')

@section('titulopanel2')
<h3>Editar y exportar nomina</h3>
@stop

@section('cuerpopanel2')
<p>Seleccione el año y mes, se desplegará la tabla de nómina correspondiente.
</p>
@include('institucion/nomina/formeditarnomina')

@if (isset($histNom) && $histNom->count())
<br>
	@include('institucion/nomina/tablaeditarnomina')

<br>

{!! Form::open(['route' => 'exportar_nomina', 'class'=>'form']) !!}
	<input type="hidden" name="mes_id" value="{{ isset($mes_selec)? $mes_selec : '' }}">
	<input type="hidden" name="anio" value="{{ isset($anio_selec)? $anio_selec : '' }}">
    {!! Form::submit('Descargar excel de nómina',['class' => 'btn btn-primary']); !!}
{!! Form::close() !!}

@endif

@stop