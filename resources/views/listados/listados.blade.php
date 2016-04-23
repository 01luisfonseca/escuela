@extends('autorizadohome')
 
@section('subcontent')
<div class="row">
  <div class="col-md-6 col-md-offset-3 vcenter">
    @if(Auth::user()->tipo_usuario_id>=4)<a class="btn btn-default btn-lg" href="{{route('listado_alumnos')}}">Listado de alumnos</a>@endif

  </div>
</div>
<div class="row">@yield('contenido')</div>
@endsection