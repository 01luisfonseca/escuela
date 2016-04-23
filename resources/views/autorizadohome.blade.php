@extends('app')
 
@section('content')
<section>
        
<div class="container-fluid">
	<div class="row">
        <div class="col-md-10 col-md-offset-1">
        	<div class="panel-group" id="accordion">
				@yield('subcontent')
			</div>
		</div>
	</div>			
</div>
</section>
@endsection