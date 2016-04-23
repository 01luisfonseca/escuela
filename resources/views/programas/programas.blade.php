@extends('autorizadohome')
 
@section('subcontent')
<div class="row">
  <div class="col-md-6 col-md-offset-3 vcenter">
    @if(Auth::user()->tipo_usuario_id>=5)<a class="btn btn-default btn-lg" href="{{route('crear_nivel')}}">Niveles o Cursos</a>
    <a class="btn btn-default btn-lg" href="{{route('crear_materia')}}">Materias o Asignaturas</a>
    <a class="btn btn-default btn-lg" href="{{route('crear_periodo')}}">Periodos Académicos</a>
    <a class="btn btn-default btn-lg" href="{{route('crear_plan')}}">Plan de estudio por niveles</a>@endif
  </div>
</div>
<div class="row">@yield('contenido')</div>
@endsection