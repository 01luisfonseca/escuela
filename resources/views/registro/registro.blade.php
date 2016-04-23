@extends('autorizadohome')
 
@section('subcontent')
<div class="row">
  <div class="col-md-6 col-md-offset-3 vcenter">
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('crear_alumno')}}">Alumnos</a>
    <a class="btn btn-default btn-lg" href="{{route('crear_profesor')}}">Profesores</a>@endif
    @if(Auth::user()->tipo_usuario_id>=4)<a class="btn btn-default btn-lg" href="{{route('crear_asistencia')}}">Asistencia</a>
    <a class="btn btn-default btn-lg" href="{{route('crear_rendimientorest')}}">Registro de notas</a>@endif
    @if(Auth::user()->tipo_usuario_id==2 || Auth::user()->tipo_usuario_id>=4)<a class="btn btn-default btn-lg" href="{{route('crear_estudiantil')}}">Registro Estudiantil</a>@endif
  </div>
</div>
<div class="row">@yield('contenido')</div>
@endsection