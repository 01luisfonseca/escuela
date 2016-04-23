@extends('panelgeneral')

@section('titulopanel')
<h3>{{$accionpension==2? 'Modificar' : 'Eliminar'}} pensión</h3>
@stop

@section('cuerpopanel')
<p>A continuación puede ver la información de la pensión seleccionada del usuario. Verifique antes de ejecutar la acción. El proceso es irreversible
</p>
<p>La pensión es de <strong>{{ $result->alumnos->users->name }} {{ $result->alumnos->users->lastname }}</strong> para el nivel <strong>{{ $result->alumnos->niveles->nombre_nivel }}</strong>.</p>
<br>
<form method="post" class="form" action="{{ route('crear_pension') }}" id="crearpension">
{!! csrf_field() !!}
<fieldset>
    <legend>Formulario de pensión</legend>
    @if($accionpension==2)
    <input type="hidden" name="modificar" value="1">
    @else
    <input type="hidden" name="eliminar" value="1">
    @endif
    <input type="hidden" name="id" value="{{ $result->id }}">    
    <input type="hidden" name="alumnos_id" value="{{ $result->alumnos_id }}">
    <div class="form-group">
        <div class="row">
        <div class="col-sm-3">
        <label class="control-label" for="valor">Código de Factura</label>
        </div>
        <div class="col-sm-9">  
        <input id="numero_factura" name="numero_factura" value="{{ $result->numero_factura }}" type="text" class="form-control">
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="mes">Mes</label> 
        </div>
        <div class="col-sm-9"> 
        <select id="mes_id" class="form-control" name="mes_id">
            @if(isset($meses) && $meses)
            @if($meses->count()>0)
                @foreach($meses as $temporal)
                    <option value="{{$temporal->id}}">{{$temporal->nombre_mes}}</option>
                @endforeach
                	<option value='{{ $result->mes_id }}' selected>Mes elegido antes: {{ $result->mes_id }}</option>
            @endif
            @endif
        </select>
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="valor">Pensión a pagar</label>
        </div>
        <div class="col-sm-9">  
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">$</span>
            <input id="valor" name="valor" type="number" value="{{ $result->valor }}" step="0.01" class="form-control">
        </div>
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="faltante">Saldo a favor de la institución, poner valor positivo. Saldo a favor del estudiante, poner número negativo</label>  
        </div>
        <div class="col-sm-9">
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">$</span>
            <input id="faltante" name="faltante" type="number" value="{{ $result->faltante }}" step="0.01" class="form-control">
        </div>
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="descripcion">Notas sobre la pensión</label>
        </div>
        <div class="col-sm-9">  
            <textarea id="descripcion" name="descripcion" cols="3" form="crearpension" class="form-control">{{ $result->descripcion }}</textarea>
        </div>
        </div>
    </div>
</fieldset>
@if($accionpension==2)
{!! Form::submit('Modificar pago de pensión',['class' => 'btn btn-warning']); !!}
@else
{!! Form::submit('Eliminar pago de pensión',['class' => 'btn btn-danger']); !!}
@endif
<a class="btn btn-info" href="{{ route('institucion') }}">Cancelar y volver</a>
</form>
@stop