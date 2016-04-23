<!-- Formulario de ingreso de usuario-->
      <div class="panel panel-default">
        <div class="panel-heading">Formulario para {{ isset($accion) ? $accion : 'registrar' }} los usuarios</div>
        <div class="panel-body">
          <form method="POST" action="
          @if(isset($accion))
          {{ route('usuarios/'.$accion) }}" class="form">
          @else
          {{ route('usuarios/registrar') }}" class="form">
          @endif
          
            {!! csrf_field() !!}

            @if(isset($user->id))
            <div class="form-group">
              <label class="control-label" for="id">ID</label>  
              <input id="id" name="mostrar_id" type="text" value="{{ $user->id }}" class="form-control input-md" disabled>
            </div>
            @endif
            <input id="id" name="id" type="hidden" value="{{ isset($user->id)? $user->id : '' }}">
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
<?php 
  if (isset($user)) {
    if ($user->id==1) {
?>
            <input type="hidden" value="$user->tipo_usuario_id" name="tipo_usuario">
<?php 
    }
  }
?>
<?php 
  if (isset($user->id)) {
    if ($user->id!=1) {
?>
            <div class="form-group">
              <label class="control-label" for="tipo_usuario">Tipo de permiso de acceso</label>
              <select id="tipo_usuario" name="tipo_usuario" class="form-control">
                @if(isset($opciones))
                @foreach($opciones as $opcion)
                <option value="{{ $opcion->id }}">{{ $opcion->nombre_tipo }}</option>
                @endforeach
                @else
                <option value="0">Ninguna</option>
                @endif
                @if(isset($user->tipo_usuario_id))
                <option value="{{ isset($user->tipo_usuario_id) ? $user->tipo_usuario_id : 0}}" selected>Opcion Actual</option>
                @endif
              </select>
            </div>
<?php 
    }
  }else{
?>
    <div class="form-group">
              <label class="control-label" for="tipo_usuario">Tipo de permiso de acceso</label>
              <select id="tipo_usuario" name="tipo_usuario" class="form-control">
                @if(isset($opciones))
                @foreach($opciones as $opcion)
                <option value="{{ $opcion->id }}">{{ $opcion->nombre_tipo }}</option>
                @endforeach
                @else
                <option value="0">Ninguna</option>
                @endif
              </select>
            </div>
<?php 
  }
?>
            @if (isset($user->password))
            
            <div class="form-group">
              @if(Auth::user()->tipo_usuario_id>=5)
              {!! Form::submit('Modificar usuario',['class' => 'btn btn-primary']) !!}
              @endif
              <a class="btn btn-info" href="{{ route('usuarios') }}">Cancelar y volver</a>
            </div>
          </form>
        </div> 
     </div>
     <div class="panel panel-default">
        <div class="panel-heading">Formulario para {{ isset($accion) ? $accion : 'registrar' }} la contraseña</div>
        <div class="panel-body">
          <form method="post" action="{{ route('usuarios/'.$accion) }}" class="form">
            {!! csrf_field() !!}
            @if(isset($user->id))
            <input name="id" type="hidden" value="{{ $user->id }}">
            <input name="cambiapass" type="hidden" value="1">
            @endif
            @include('contrasena')
            <div class="form-group">
              {!! Form::submit('Modificar Contraseña',['class' => 'btn btn-primary']) !!}
              <a class="btn btn-info" href="{{ route('usuarios') }}">Cancelar y volver</a>
            @else

            @include('contrasena')
            <div class="form-group">
              {!! Form::submit('Registrar',['class' => 'btn btn-primary']) !!}

            @endif
            </div>
          </form>
        </div> 
     </div>

<!-- FIN Formulario de ingreso de usuario-->