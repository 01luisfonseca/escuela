@extends('autorizadohome')
 
@section('subcontent')

	@include('registro/rendimiento/panelboletines')
	<script src="{{ route('public') }}/js/registro/boletines.js"></script>
	
@endsection