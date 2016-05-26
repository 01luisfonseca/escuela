@extends('panelgeneral')

@section('titulopanel')
<h3>Actividades de mantenimiento general de la aplicación</h3>
@stop

@section('cuerpopanel')
<script src='/public/js/mantenimiento.js'></script>
<div ng-app='mantenimiento'><mtto-dir></mtto-dir></div>
@stop