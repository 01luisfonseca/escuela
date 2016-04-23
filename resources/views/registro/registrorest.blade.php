@extends('autorizadohome')
 
@section('subcontent')

	@include('registro/rendimiento/panelregistrorest')
	<script src="{{ route('public') }}/js/registro/notas.js"></script>
	
@endsection