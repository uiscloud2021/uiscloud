@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<h4 class="m-0">Editar Usuario</h4>
@stop

@section('content')
@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif
<div class="card">
        <div class="card-body">
            {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Nombre', ['class' => 'form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del usuario']) !!}
                
                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Correo electrónico', ['class' => 'form-label']) !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo electrónico']) !!}
                
                    @error('email')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('password', 'Nueva contraseña', ['class' => 'form-label']) !!}
                    {!! Form::hidden('password', null, ['class' => 'form-control']) !!}
                    {!! Form::text('new_password', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nueva contraseña']) !!}
                
                    @error('password')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('role', 'Listado de roles', ['class' => 'form-label']) !!}

                    @foreach ($roles as $role)
                        <div>
                            <label>
                                {!! Form::checkbox('role[]', $role->id, $user->hasRole($role->id), null, ['class' => 'mr-1']) !!}
                                {{$role->name}}
                            </label>
                        </div>
                    @endforeach
                
                    @error('role')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('position', 'Puesto', ['class' => 'form-label']) !!}
                    {!! Form::text('position', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el puesto']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('phone', 'Teléfono', ['class' => 'form-label']) !!}
                    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el teléfono']) !!}
                </div>

                <a href="/users" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    
@stop