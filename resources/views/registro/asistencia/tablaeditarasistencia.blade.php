@extends('panelgeneral3')

@section('cuerpopanel3')
<p>{{ !isset($result)? 'No h' : 'H' }}ay {{ $result->count() }} resultado(s).
</p>
<div class="table-responsive">
	<table class="table table-condensed table-striped">
		<thead>
                    <tr>
                        <th>Nombre de Empleado</th>
                        <th>Identificacion</th>
                        <th>Salario</th>
                        <th>EPS</th>
                        <th>% EPS</th>
                        <th>ARL</th>
                        <th>% ARL</th>
                        <th>Pensión</th>
                        <th>% Pensión</th>
                    </tr>
        </thead>
        <tbody>
                    @foreach($result as $campo)
                    <tr>
                        <td>{{ $campo->users->name }} {{ $campo->users->lastname }}</td>
                        <td>{{ $campo->users->identificacion }}</td>
                        <td>{{ $campo->salario }}</td>
                        <td>{{ $campo->eps }}</td>
                        <td>{{ $campo->eps_val }}</td>
                        <td>{{ $campo->arl }}</td>
                        <td>{{ $campo->arl_val }}</td>
                        <td>{{ $campo->pension }}</td>
                        <td>{{ $campo->pension_val }}</td>
                        <td>
                            <form method="POST" action="{{ route('editar_empleado')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <input type="hidden" name="accion" value="0">
                                <button class="btn btn-primary btn-xs">Modificar</button>
                            </form>
                            <form method="POST" action="{{ route('editar_empleado')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="accion" value="1">
                                <button class="btn btn-danger btn-xs">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
        </tbody>
    </table>
</div>
@stop