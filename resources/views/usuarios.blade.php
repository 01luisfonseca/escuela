@extends('autorizadohome')
 
@section('subcontent')

<div class="row">
  <div class="col-md-6 col-md-offset-3 vcenter">
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('usuarios/crear')}}">Crear usuario</a>@endif
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('usuarios/modificar')}}">Modificar usuario</a>@endif
    <a class="btn btn-default btn-lg" href="{{route('usuarios/carnet')}}">Carnetización</a>
  </div>
</div>
@endsection
