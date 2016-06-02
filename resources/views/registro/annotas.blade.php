@extends('autorizadohome')
 
@section('subcontent')

	@include('registro/rendimiento/panelannotas')
	<script src="{{ route('public') }}/js/registro/annotas.js"></script>
	
@endsection