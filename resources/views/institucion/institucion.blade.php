@extends('autorizadohome')
 
@section('subcontent')
<div class="row">
  <div class="col-md-6 col-md-offset-3 vcenter">
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('crear_empleado')}}">Empleados</a>@endif
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('crear_pension')}}">Pensiones</a>@endif
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('crear_matricula')}}">Matrículas</a>@endif
    @if(Auth::user()->tipo_usuario_id>=6)<a class="btn btn-default btn-lg" href="{{route('crear_nomina')}}">Nómina</a>@endif
    @if(Auth::user()->tipo_usuario_id>=6)<a class="btn btn-default btn-lg" href="{{route('crear_estado')}}">Estado Financiero</a>@endif
  </div>
</div>
<div class="row">@yield('contenido')</div>
@endsection