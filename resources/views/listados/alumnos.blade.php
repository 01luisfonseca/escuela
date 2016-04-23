@extends('autorizadohome')
 
@section('subcontent')

	@include('listados/alumnos/listadoalumnos')
	<script src="{{ route('public') }}/js/listados/listados.js"></script>
	
@endsection