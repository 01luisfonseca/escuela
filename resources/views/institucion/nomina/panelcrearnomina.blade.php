@extends('panelgeneral')

@section('titulopanel')
<h3>Liquidador de nómina</h3>
@stop

@section('cuerpopanel')
<p>En este lugar se podrá calcular la nómina de cada empleado, según la lista de empleados. Una vez liquidada, se puede registrar el pago en la siguiente sección.
</p>
<br>
@include('institucion/nomina/formcrearnomina')
@stop