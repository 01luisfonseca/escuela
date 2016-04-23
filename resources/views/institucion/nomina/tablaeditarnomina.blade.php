@extends('panelgeneral3')

@section('cuerpopanel3')

<div class="table-responsive">
	<table class="table table-condensed table-striped">
		<thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Salario del contrato</th>
                        <th>Dias</th>
                        <th class="success">Salario devengado</th>
                        <th class="success">Sub/Trans</th>
                        <th class="success">Bonificaciones</th>
                        <th class="success">Total devengado</th>
                        <th class="warning">Salud 4%</th>
                        <th class="warning">Pensión 4%</th>
                        <th class="warning">Deducciones</th>
                        <th class="warning">Total Deducción</th>
                        <th class="info">Neto a pagar</th>
                        <th>Fecha de pago</th>
                    </tr>
        </thead>
        <tbody>
                @foreach($histNom as $campo)
                    <tr>
                        <td>{{ $campo->empleados->users->identificacion }}</td>
                        <td>{{ $campo->empleados->users->lastname }}</td>
                        <td>{{ $campo->empleados->users->name }}</td>
                        <td>{{ $campo->empleados->salario }}</td>
                        <td>{{ $campo->dias }}</td>
                        <td>{{ $campo->salario_pagado }}</td>
                        <td>{{ $campo->auxmovil }}</td>
                        <td>{{ $campo->bonificacion }}</td>
                        <td>{{ $campo->salario_pagado+$campo->auxmovil+$campo->bonificacion }}</td>
                        <td>{{ $campo->eps_empleado }}</td>
                        <td>{{ $campo->pension_empleado }}</td>
                        <td>{{ $campo->descuento }}</td>
                        <td>{{ $campo->eps_empleado+$campo->pension_empleado+$campo->descuento }}</td>
                        <td>{{ $campo->salario_pagado+$campo->auxmovil+$campo->bonificacion-$campo->eps_empleado-$campo->pension_empleado-$campo->descuento }}</td>
                        <td>{{ $campo->pagado_at }}</td>
                        <td>
                            
                            @if(isset($campo->pagado_at))
                            <form method="POST" action="{{ route('editar_nomina')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="accionNomina" value="2">
                                <button class="btn btn-info btn-xs">Registrar Pago</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('editar_nomina')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <input type="hidden" name="accionNomina" value="0">
                                <button class="btn btn-warning btn-xs">Modificar</button>
                            </form>
                            @if(isset($campo->pagado_at))
                            <form method="POST" action="{{ route('editar_nomina')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="accionNomina" value="1">
                                <button class="btn btn-danger btn-xs">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
@stop