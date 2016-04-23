<!DOCTYPE html>
<html lang="es">
<head>
	<script src="{{ route('public') }}/assets/js/angular.min.js"></script>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Escuela</title>
	<!-- Bootstrap CDN -->
 	<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/bootstrap.min.css">
 	<!-- Estilo Propio -->
 	<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/app.css">
 	<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/vertical-tabs.css">
 	<link rel="icon" type="image/ico" href="{{ route('public') }}/favicon.ico">


</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Administración de escuela</a>
			</div>	
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
			    @if (!Auth::guest())
			    @if(Auth::user()->tipo_usuario_id>1)
			    	<li class="dropdown">
			    		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"  aria-haspopup="true" aria-expanded="false">
			    		Gestión Escolar <span class="caret"></span>	</a>
			    		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
				    		<li class="dropdown-submenu">
	                        	<a href="{{route('usuarios')}}" class="dropdown-toggle">Usuarios</a>
	                        	<ul class="dropdown-menu">
	                        		@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('usuarios/crear')}}">Crear usuario</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('usuarios/modificar')}}">Modificar usuario</a></li>@endif
	                	  			<li><a href="{{route('usuarios/carnet')}}">Carnetización</a></li>
	                   			</ul>
	                 		</li>
	                 		@if(Auth::user()->tipo_usuario_id>=5)
	                 		<li class="dropdown-submenu">
	                        	<a href="{{route('programas')}}" class="dropdown-toggle">Programas Educativos</a>
	                    		<ul class="dropdown-menu">
	                        		<li><a href="{{route('crear_nivel')}}">Niveles o Cursos</a></li>
	                        		<li><a href="{{route('crear_materia')}}">Materias o Asignaturas</a></li>
	                        		<li><a href="{{route('crear_periodo')}}">Periodos Académicos</a></li>
	                        		<li><a href="{{route('crear_plan')}}">Plan de estudio por niveles</a></li>
	                    		</ul>
	                    	</li>
	                    	@endif
	                    	<li class="dropdown-submenu">
	                        	<a href="{{route('registro')}}" class="dropdown-toggle">Registro Académico</a>
	                    		<ul class="dropdown-menu">
	                    			@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('crear_alumno')}}">Alumnos</a></li>@endif
	                    			@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('crear_profesor')}}">Profesores</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=4)<li><a href="{{route('crear_asistencia')}}">Asistencia</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=4)<li><a href="{{route('crear_rendimientorest')}}">Registro de notas</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id==2)<li><a href="{{route('crear_estudiantil')}}">Registro estudiantil</a></li>@endif
	                    		</ul>
	                    	</li>
	                    	@if(Auth::user()->tipo_usuario_id>=4)
	                    	<li class="dropdown-submenu">
	                        	<a href="{{route('programas')}}" class="dropdown-toggle">Listados</a>
	                    		<ul class="dropdown-menu">
	                        		<li><a href="{{route('listado_alumnos')}}">Alumnos en niveles</a></li>
	                    		</ul>
	                   	 	</li>
	                    	@endif
	                   		 @if(Auth::user()->tipo_usuario_id>=4)
	                    	<li class="dropdown-submenu">
	                        	<a href="{{route('institucion')}}" class="dropdown-toggle">Administración Institucional</a>
	                    		<ul class="dropdown-menu">
	                    			@if(Auth::user()->tipo_usuario_id>=6)<li><a href="{{route('crear_empleado')}}">Empleados</a></li>@endif
	                    			@if(Auth::user()->tipo_usuario_id>=6)<li><a href="{{route('crear_nomina')}}">Nómina</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('crear_pension')}}">Pensiones</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=5)<li><a href="{{route('crear_matricula')}}">Matrículas</a></li>@endif
	                        		@if(Auth::user()->tipo_usuario_id>=6)<li><a href="{{route('crear_estado')}}">Estado Financiero</a></li>@endif
	                    		</ul>
	                    	</li>
	                    	@endif
	                	</ul>
				    </li>
				    @if(Auth::user()->tipo_usuario_id>=6)
				    <li class="dropdown">
				    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"  aria-haspopup="true" aria-expanded="false">Gestión de pagos<span class="caret"></span></a>
			    		<ul class="dropdown-menu">
				    		@if(Auth::user()->tipo_usuario_id>=6)<li><a href="{{route('gestion-pagos')}}" class="dropdown-toggle">Gestión central</a></li>@endif
				    	</ul>
				    </li>
				    @endif
			    	@if(Auth::user()->tipo_usuario_id>=5)
			    	<li class="dropdown">
			    		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"  aria-haspopup="true" aria-expanded="false">Consulta de resultados <span class="caret"></span></a>
			    		<ul class="dropdown-menu">
				    		<li><a href="{{route('home_boletin')}}" class="dropdown-toggle">Boletines</a></li>
				    	</ul>
				    </li>
			    	@endif
				@endif
			    @endif	    
				@if (Auth::guest())
				    <li>
				    	<a class="btn btn-default btn-lg btn-alert" href="{{route('auth/login')}}">Iniciar Sesión</a>
				    </li>
				@else
		            <li>
		              	<a class="btn btn-default btn-alert" href="{{ route('usuarios/editar') }}/{{Auth::user()->id}}">Hola, <em>{{Auth::user()->name}} {{Auth::user()->lastname}}</em></a>
		            </li>
		            <li>
		            	<a class="btn btn-default btn-danger" href="{{route('auth/logout')}}">Cerrar Sesión</a>
		            </li>   
			    @endif				    
				</ul>
			</div>
		</div>
	</nav>
 	<div id="notificaciones" class="container" style='margin-top: 80px'>
 	@if (Session::has('flash_message'))
 	<div class="alert alert-info" role="alert">
 		<ul><strong>Mensaje informativo : </strong>
 			<li>{{ Session::get('flash_message') }}</li>
 		</ul>
	</div>
	@endif
	@if (isset($mensajeResultado))
		@if($mensajeResultado)
	<div class="alert alert-warning" role="alert">
 		<p><strong>Resultado: </strong></p>
 		<p>{{ $mensajeResultado }}</p>
	</div>
		@endif
	@endif
     @if (Session::has('errors'))
     	@if($errors->count()<>0)
	    <div class="alert alert-danger" role="alert">
		<ul>
	    <strong>Oops! Tenemos un error : </strong>
	    @foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
	    @endforeach
		</ul>
	    </div>
	    @endif
		@endif
    </div>
	<section>
		@yield('content')
	</section>    
 
	<!-- Scripts -->
	<!-- jQuery -->
	<script src="{{ route('public') }}/assets/js/jquery-1.11.3.min.js"></script>

	<!-- Latest compiled and minified JavaScript Bootstrap-->
	<script src="{{ route('public') }}/assets/js/bootstrap.min.js"></script>
	<script src="{{ route('public') }}/assets/js/angular-confirm.js"></script>
	<script src="{{ route('public') }}/assets/js/ui-bootstrap-1.1.2.min.js"></script>
	<script src="{{ route('public') }}/js/app.js"></script>
	<script src="{{ route('public') }}/js/listas.js"></script>
	<script src="{{ route('public') }}/js/pagos/pagos.js"></script>

	<!-- Latest compiled and minified JavaScript jQueryUI-->
	<script src="{{ route('public') }}/assets/js/jquery-ui.min.js"></script>

</body>
</html>