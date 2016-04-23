@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Formulario de ingreso</div>
                    <div class="panel-body">
                        <form method="POST" action="/auth/login" class="form">
                        {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Documento de identificación</label>
                                {!! Form::text('identificacion', '', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                {!! Form::password('password', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="checkbox">
                                <label>{!! Form::checkbox('remember', 'Recordarme') !!}Recordarme</label>
                            </div>
                            <div>                            
                                {!! Form::submit('Entrar',['class' => 'btn btn-primary']) !!}
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
@endsection