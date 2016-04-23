@extends('facturatirilla')

@section('titulo')
Factura de {{ $tipo_factura }}
@stop

@section('body')
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><strong>No. FACTURA. {{$result->numero_factura}}</strong></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><h6>NUEVO COLEGIO LUSADI LTDA</h6></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<img class="emblemas" src="{{ route('public')}}/assets/img/escudo.png">
		<img class="emblemas" src="{{ route('public')}}/assets/img/logo.png">
	</div>
	<div class="col-xs-8">
		<div class="col-xs-12">
			<p class="text-center"><b><small>NIT 900185143-3</small></b></p>
		</div>
		<div class="col-xs-12">
			<p class="text-center"><small>{{ $result->cancelado_at }}</small></p>
		</div>
		<div class="col-xs-12">
			<p class="text-center"><small><b>DANE 311001000514</b></small></p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-8 col-xs-offset-2">
		<p class="text-center"><h6><small>APROBACION 7562 DE NOV 24/98</small></h6></p>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-4">
		<p class="text-center"><h6>CURSO:</h6></p>
	</div>
	<div class="col-xs-8">
		<p class="text-center"><small>{{$result->alumnos->niveles->nombre_nivel}}</small></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<p class="text-center"><h6>ALUMNO:</h6></p>
	</div>
	<div class="col-xs-8">
		<p class="text-center"><small>{{$result->alumnos->users->name}} {{$result->alumnos->users->lastname}}</small></p>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-8 col-xs-offset-2">
		<p class="text-center"><strong><small>MOTIVO DEL PAGO</small></strong></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-condensed table-bordered">
			<tbody>
				<tr>
					<td><h5><small>MATRICULA</small></h5></td>
					<td></td>
					<td><h5><small>{{isset($valor_matri)? $valor_matri : ''}}</small></h5></td>
				</tr>
				<tr>
					<td><h5><small>PENSION</small></h5></td>
					<td><h5><small>{{isset($mes_pen)? $mes_pen : ''}}</small></h5></td>
					<td><h5><small>{{isset($valor_pen)? $valor_pen : ''}}</small></h5></td>
				</tr>
				<tr>
					<td><h5><small>OTROS</small></h5></td>
					<td><h5><small></small></h5></td>
					<td><h5><small>{{isset($valor_opa)? $valor_opa : ''}}</small></h5></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td><h5><small>TOTAL:</small></h5></td>
					<td colspan="2"><h5><small>{{ $result->valor }}</small></h5></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-xs-10 col-xs-offset-1">
		<p class="text-justify"><h6>OBSERVACIONES: <small>{{ $result->descripcion }}</small></h6></p>
	</div>
</div>
<br>
<br>
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><h5><small>RECIBI:_____________________</small></h5></p>
	</div>
</div>
@stop