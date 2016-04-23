@extends('facturatirilla')

@section('titulo')
Tirilla de caja
@stop

@section('body')
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><strong>CIERRE DE CAJA</strong></p>
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
			<p class="text-center"><small>{{ $fechaAhora }}</small></p>
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
		<p class="text-center"><h6>ENTREGA:</h6></p>
	</div>
	<div class="col-xs-8">
		<p class="text-center"><small>_________________</small></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<p class="text-center"><h6>RECIBE:</h6></p>
	</div>
	<div class="col-xs-8">
		<p class="text-center"><small>_________________</small></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<p class="text-center"><h6>DÍA DE CIERRE:</h6></p>
	</div>
	<div class="col-xs-8">
		<p class="text-center"><h5><small>{{$tFecha}}</small></h5></p>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-8 col-xs-offset-2">
		<p class="text-center"><strong><small>VALORES TOTALES</small></strong></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-condensed table-bordered">
			<tbody>
				<tr>
					<td><h5><small>MATRICULA</small></h5></td>
					<td><h5><small>{{$tMatri}}</small></h5></td>
				</tr>
				<tr>
					<td><h5><small>PENSION</small></h5></td>
					<td><h5><small>{{$tPension}}</small></h5></td>
				</tr>
				<tr>
					<td><h5><small>OTROS</small></h5></td>
					<td><h5><small>{{$tOtro}}</small></h5></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td><h5><small>TOTAL:</small></h5></td>
					<td><h5><small>{{ $tTotal }}</small></h5></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-xs-10 col-xs-offset-1">
		<p class="text-justify"><h6>OBSERVACIONES:</h6></p>
	</div>
</div>
<br>
<br>
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><h5><small>FIRMA ENTREGA:_____________________</small></h5></p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><h5><small>FIRMA RECIBE:_____________________</small></h5></p>
	</div>
</div>
@stop