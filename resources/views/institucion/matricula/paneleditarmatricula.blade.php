@extends('panelgeneral')

@section('titulopanel')
<h3>Gestión de matrícula</h3>
@stop

@section('cuerpopanel')
<p>A continuación puede ver la  matrícula del usuario. Puede consultarla, modificarla o imprimir el recibo.
</p>
<p>Ajuste de matrícula de <strong>{{ $alumno->users->name }} {{ $alumno->users->lastname }}</strong> para el nivel <strong>{{ $alumno->niveles->nombre_nivel }}</strong>. Puede crear la matrícula y verificar la creada y pagada</p>
<br>
<form method="post" class="form" action="{{ route('crear_matricula') }}" id="crearmatricula">
{!! csrf_field() !!}
<fieldset>
    <legend>Formulario de creación de pensión</legend>
    <input type="hidden" name="alumnos_id" value="{{ $alumno->id }}">
    <div class="form-group">
        <div class="row">
        <div class="col-sm-3">
        <label class="control-label" for="valor">Código de Factura</label>
        </div>
        <div class="col-sm-9">  
        <input id="numero_factura" name="numero_factura" type="text" class="form-control">
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="valor">Matrícula a pagar</label>
        </div>
        <div class="col-sm-9">  
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">$</span>
            <input id="valor" name="valor" type="number" value="{{ $alumno->matricula }}" step="0.01" class="form-control">
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
            <input id="faltante" name="faltante" type="number" step="0.01" class="form-control">
        </div>
        </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
    	<div class="col-sm-3">
        <label class="control-label" for="descripcion">Notas sobre la matrícula</label>
        </div>
        <div class="col-sm-9">  
            <textarea id="descripcion" name="descripcion" cols="3" form="crearmatricula" class="form-control"></textarea>
        </div>
        </div>
    </div>
</fieldset>
{!! Form::submit('Crear pago de matrícula',['class' => 'btn btn-primary']); !!}
</form>
<br>
<br>        

@if (isset($result) && $result->count())
<br>
	@include('institucion/matricula/tablaactualmatricula')

<br>

@endif
<a class="btn btn-primary" href="{{ route('institucion') }}">Volver al inicio</a>
@stop