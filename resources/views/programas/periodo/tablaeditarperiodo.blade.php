@extends('panelgeneral3')

@section('cuerpopanel3')
<p>{{ !isset($result)? 'No h' : 'H' }}ay {{ $result->count() }} resultado(s).
</p>
<div class="table-responsive">
	<table class="table table-condensed table-striped">
		<thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
        </thead>
        <tbody>
                    @foreach($result as $campo)
                    <tr>
                        <td>{{ $campo->nombre_periodo }}</td>
                        <td>{{ $campo->fecha_ini }}</td>
                        <td>{{ $campo->fecha_fin }}</td>
                        <td>{{ $campo->descripcion }}</td>
                        <td>
                            <form method="POST" action="{{ route('editar_periodo')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <input type="hidden" name="accion" value="0">
                                <button class="btn btn-primary btn-xs">Modificar</button>
                            </form>
                            <form method="POST" action="{{ route('editar_periodo')}}">
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