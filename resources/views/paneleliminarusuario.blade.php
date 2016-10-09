@extends('panelgeneral')

@section('titulopanel')
<h3>Elimininar definitivamente el usuario</h3>
@stop

@section('cuerpopanel')

			{!! Form::open(['route'=>'usuarios/eliminar', 'method'=>'POST', 'class'=>'form']) !!}
			<fieldset>
			<legend>¿Desea eliminar definitivamente el siguiente usuario?</legend>
			<input id="id" name="id" type="hidden" value="{{ $user->id }}">
            <div class="form-group">
              <label class="control-label" for="name">Nombres</label>  
              <input id="name" name="name" type="text" placeholder="Digite nombres" value="{{ isset($user->name) ? $user->name : ''}}" class="form-control input-md" required="">
            </div>
            <div class="form-group">
              <label class="control-label" for="lastname">Apellidos</label>  
              <input id="lastname" name="lastname" type="text" placeholder="Digite los apellidos" value="{{ isset($user->lastname) ? $user->lastname : ''}}" class="form-control input-md" required="">
            </div>
            <div class="form-group">
              <label class="control-label" for="identificacion">No. Identificacion</label>  
              <input id="identificacion" name="identificacion" type="number" placeholder="Introduzca su numero de identificación" value="{{ isset($user->identificacion) ? $user->identificacion : ''}}" class="form-control input-md" required="">
            </div>
            <div class="form-group">
              <label class="control-label" for="birday">Fecha de nacimiento</label>  
              <input id="birday" name="birday" type="date" placeholder="dd/mm/aaaa" value="{{ isset($user->birday) ? $user->birday : ''}}"class="form-control input-md"> 
            </div>
            <div class="form-group">
              <label class="control-label" for="email">Correo electrónico</label>  
              <input id="email" name="email" type="email" placeholder="correo@dominio.com" value="{{ isset($user->email) ? $user->email : ''}}"class="form-control input-md">    
            </div>
            <div class="form-group">
              <label class="control-label" for="telefono">Teléfono</label>  
              <input id="telefono" name="telefono" type="text" placeholder="xx x xxxxxxx" value="{{ isset($user->telefono) ? $user->telefono : ''}}" class="form-control input-md" required="">   
            </div>
            <div class="form-group">
              <label class="control-label" for="direccion">Dirección de residencia</label>  
              <input id="direccion" name="direccion" type="text" placeholder="CLL 19 Z BIS SUR # 20 A BIS ESTE 30" value="{{ isset($user->direccion) ? $user->direccion : ''}}"class="form-control input-md" required="">    
            </div>
            <div class="form-group">
              <label class="control-label" for="acudiente">Nombre de Acudiente</label>  
              <input id="acudiente" name="acudiente" type="text" placeholder="Nombre de persona a cargo" value="{{ isset($user->acudiente) ? $user->acudiente : ''}}"class="form-control input-md">   
            </div>
            <div class="form-group">
              <label class="control-label" for="tarjeta">Tarjeta</label>  
              <input id="tarjeta" name="tarjeta" type="text" placeholder="Codigo de tarjeta" value="{{ isset($user->tarjeta) ? $user->tarjeta : ''}}"class="form-control input-md">   
            </div>
            {!! Form::submit('Eliminar definitivamente el usuario',['class' => 'btn btn-danger']) !!}
            <a class="btn btn-info" href="{{ route('usuarios/modificar') }}">Cancelar y volver</a>
            </fieldset>
            {!! Form::close() !!}

@stop