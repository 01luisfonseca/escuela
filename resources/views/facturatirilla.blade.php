<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>@yield('titulo')</title>
 	<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/app.css">
  </head>
  <body>
  	<div class="facturatirilla">
  	<div class="container-fluid">
  	@yield('body')
  	</div>
  	</div>
  </body>
</html>