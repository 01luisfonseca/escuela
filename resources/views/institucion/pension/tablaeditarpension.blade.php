@extends('panelgeneral3')

@section('cuerpopanel3')

<div class="table-responsive">
	<table class="table table-condensed table-striped">
		<thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Nivel relacionado</th>
                        <th>Valor de pago de pensión</th>
                        <th>Acción</th>
                    </tr>
        </thead>
        <tbody>
                @foreach($result as $campo)
                    <tr>
                        <td> <a class="label label-success" href="{{ route('getactualizar_alumno') }}/{{ $campo->id }}">{{ $campo->identificacion }}</a></td>
                        <td> {{ $campo->lastname }} </td>
                        <td> {{ $campo->name }} </td>
                        <td> {{ $campo->nombre_nivel }} </td>
                        <td> {{ $campo->pension }} </td>
                        <td>
                            <form method="POST" action="{{ route('editar_pension')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $campo->id }}">
                                <input type="hidden" name="nombre_nivel" value="{{$campo->nombre_nivel}}">
                                <input type="hidden" name="accionPension" value="0">
                                <button class="btn btn-primary btn-xs">Ver pensiones</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
@stop