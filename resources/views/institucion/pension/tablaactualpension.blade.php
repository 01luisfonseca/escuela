@extends('panelgeneral2')

@section('cuerpopanel2')

<div class="table-responsive">
	<table class="table table-condensed table-striped">
		<thead>
                    <tr>
                        <th>No. Factura</th>
                        <th>Mes</th>
                        <th>Valor de pago de pensión</th>
                        <th>Fecha de pago</th>
                        <th>Estado de pago</th>
                        <th>Acción</th>
                    </tr>
        </thead>
        <tbody>
                @foreach($result as $campo)
                    <tr>
                        <td>{{ $campo->numero_factura }}</td>
                        <td>{{ $campo->mes_id }}</td>
                        <td>{{ $campo->valor }}</td>
                        <td>{{ $campo->cancelado_at }}</td>
                        <td>@if($campo->faltante==0)
                            Pagada
                            @else
                                @if($campo->faltante>0)
                                Saldo pendiente: {{ $campo->faltante }}
                                @else
                                Pagada y con saldo a favor: {{ $campo->faltante }}
                                @endif
                            @endif</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{{ route('imprimir_pension') }}/{{ $campo->id }}" target="_blank">Imprimir Tirilla</a>
                            <form method="POST" action="{{ route('editar_pension')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="accionPension" value="2">
                                <button class="btn btn-warning btn-xs">Modificar</button>
                            </form>
                            <form method="POST" action="{{ route('editar_pension')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="accionPension" value="3">
                                <button class="btn btn-danger btn-xs">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
@stop