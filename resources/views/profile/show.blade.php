@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
<h4 class="m-0">Perfil de usuario</h4>
@stop

@section('content')

<div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row d-flex align-items-stretch">

            <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Datos del usuario
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>{{$user->name}}</b></h2>
                      <p class="text-muted text-sm"><b>Puesto: </b> {{$user->position}} </p>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Empresa: Unidad de Investigación en Salud</li><br/>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Teléfono #: +52 - {{$user->phone}}</li><br/>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> Correo: {{$user->email}}</li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                      <img src="https://picsum.photos/300/300" alt="" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card bg-light">
                <div class="card-header text-muted border-bottom-0">
                  Cambiar datos
                </div>
                {!! Form::model($user, ['route' => ['profile.update', $user], 'method' => 'put']) !!}
                <div class="card-body">
                        
                        <div class="form-group">
                            {!! Form::label('name', 'Nombre', ['class' => 'form-label']) !!}
                            {!! Form::hidden('id', null, ['class' => 'form-control']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del usuario']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('password', 'Nueva contraseña', ['class' => 'form-label']) !!}
                            {!! Form::hidden('password', null, ['class' => 'form-control']) !!}
                            {!! Form::text('new_password', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nueva contraseña']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('phone', 'Teléfono', ['class' => 'form-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el teléfono']) !!}
                        </div>

                </div>
                <div class="card-footer">
                  <div class="text-right">
                    {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
			  
          </div>
        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    
@stop
