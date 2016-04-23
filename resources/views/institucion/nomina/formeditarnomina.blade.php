@extends('formgeneral2')

@section('formheader2')
id="editarnivel" method="POST" action"{{ route('crear_nomina') }}" 
@stop

@section('forminterior2')
<fieldset>
	<legend>Seleccione mes y año para verificar la nómina: </legend>
	<div class="form-group">
		<label class="control-label" for="anio">Año</label>  
	    <input id="anio" name="anio" type="number" step="1" value="2015" class="form-control input-md">
	</div>
	<div class="form-group">
		<label class="control-label" for="mes">Mes</label>  
	    <select id="mes_id" class="form-control" name="mes_id">
	    	@if(isset($meses) && $meses)
	    	@if($meses->count()>0)
	    		@foreach($meses as $temporal)
	    			<option value="{{$temporal->id}}">{{$temporal->nombre_mes}}</option>
	    		@endforeach
	    	@endif
	    	@endif
	    </select>
	</div>
	{!! Form::submit('Buscar',['class' => 'btn btn-primary']) !!}
</fieldset>
@stop