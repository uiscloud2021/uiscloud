@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
    <h4 class="m-0">Nuevo Archivo</h4>
@stop

@section('content')
<div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'files.store', 'autocomplete' => 'off', 'files' => true]) !!}

            {!! Form::hidden('user_id', auth()->user()->id) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Nombre', ['class' => 'form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del archivo']) !!}
                
                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('archivo', 'Archivo', ['class' => 'form-label']) !!}
                    {!! Form::file('archivo', ['class' => 'form-control-file', 'enctype' => 'multipart/form-data']) !!}
                
                    @error('archivo')
                        <span class="text-danger">{{$message}}</span>
                    @enderror

                </div>

                <div class="form-group" style="display:none">
                    {!! Form::label('size', 'Tamaño del archivo', ['class' => 'form-label']) !!}
                    {!! Form::text('size', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tamaño del archivo', 'readonly']) !!}
                </div>

                <div class="form-group" style="display:none">
                    {!! Form::label('type', 'Extensión del archivo', ['class' => 'form-label']) !!}
                    {!! Form::text('type', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de archivo', 'readonly']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('category_id', 'Carpeta donde se almacenará el archivo', ['class' => 'form-label']) !!}
                    {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
                
                    @error('category_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    <p class="font-weight-bold">Usuarios que tendrán acceso al acrhivo</p>
                    @foreach ($users as $user)
                        <label class="mr-2">
                            {!! Form::checkbox('users[]', $user->id, null) !!}
                            {{$user->name}}
                        </label><br/>
                    @endforeach

                    @error('users')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                

                <a href="/files" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Guardar archivo', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop