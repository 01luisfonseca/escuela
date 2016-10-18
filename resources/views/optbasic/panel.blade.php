@extends('panelgeneral')

@section('titulopanel')
<h3>Opciones generales de la aplicación</h3>
@stop

@section('cuerpopanel')
<script src='/public/js/optbasic/optbasic.js'></script>
<div ng-app='opciones'><opt-dir></opt-dir></div>
@stop