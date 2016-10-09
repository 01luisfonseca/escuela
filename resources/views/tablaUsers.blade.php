@extends('resultbusqueda')
 
@section('tabla')
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Identificación</th>
                        <th>Fecha de nacimiento</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acudiente</th>
                        <th>Nivel de acceso</th>
                        <th>Tarjeta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $campo)
                    @if($campo->id!=1)
                    <tr>
                        <td>{{ $campo->name }}</td>
                        <td>{{ $campo->lastname }}</td>
                        <td>{{ $campo->identificacion }}</td>
                        <td>{{ $campo->birday }}</td>
                        <td>{{ $campo->email }}</td>
                        <td>{{ $campo->telefono }}</td>
                        <td>{{ $campo->direccion }}</td>
                        <td>{{ $campo->acudiente }}</td>
                        <td>{{ $campo->tipo_usuario->nombre_tipo }}</td>
                        <td>{{ $campo->tarjeta }}</td>
                        <td>
                            <form method="POST" action="{{ route('usuarios/editar')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="accionusuario" value="1">
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <button class="btn btn-primary btn-xs">Modificar</button>
                            </form>
                            <form method="POST" action="{{ route('usuarios/eliminar')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="accionusuario" value="2">
                                <input type="hidden" name="id" value="{{$campo->id}}">
                                <button class="btn btn-danger btn-xs">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
@endsection
