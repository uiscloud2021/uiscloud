@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
    <h4 class="m-0">Editar Archivo</h4>
@stop

@section('content')
@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif
<div class="card">
        <div class="card-body">
            {!! Form::model($file, ['route' => ['files.update', $file], 'method' => 'put', 'files' => true]) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Nombre', ['class' => 'form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del archivo']) !!}
                
                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('archivo', 'Archivo actual', ['class' => 'form-label']) !!}

                    @if ($file->filename)
                        <br/>
                        <a href="{{$file->url}}" target="_blank">{{$file->name}}.{{$file->type}}</a>
                     @endif
                </div>

                <div class="form-group">
                    {!! Form::label('archivo', 'Nuevo archivo', ['class' => 'form-label']) !!}
                    {!! Form::file('archivo', ['class' => 'form-control-file', 'enctype' => 'multipart/form-data']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('size', 'Tamaño del archivo actual', ['class' => 'form-label']) !!}
                    {!! Form::text('size', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tamaño del archivo', 'readonly']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('type', 'Extensión del archivo actual', ['class' => 'form-label']) !!}
                    {!! Form::text('type', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de archivo', 'readonly']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('category_id', 'Directorio principal donde se almacenará el archivo', ['class' => 'form-label']) !!}
                    {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
                
                    @error('category_id')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                
                </div>

                <div class="form-group">
                    {!! Form::label('versionamiento', 'Requiere versionamiento (No = bloquea el archivo si alguien más lo está usando)', ['class' => 'form-label']) !!}
                    {!! Form::select('versionamiento', [null => 'Seleccione'] + ['Si' => 'Si', 'No'=>'No'], null, ['class' => 'form-control']) !!}
                    
                    @error('versionamiento')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    {!! Form::label('bloqueado', 'Archivo bloqueado (Si = El archivo no se podrá descargar)', ['class' => 'form-label']) !!}
                    {!! Form::select('bloqueado', [null => 'Seleccione'] + ['0' => 'No', '1'=>'Si'], null, ['class' => 'form-control']) !!}
                    <br/>Bloqueado por: {!! Form::text('user_block', null, ['class' => 'form-control', 'readonly']) !!}
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

                <div class="form-group">
                    {!! Form::label('details', 'Descripción', ['class' => 'form-label']) !!}
                    {!! Form::textarea('details', null, ['class' => 'form-control', 'placeholder' => 'Descripción o detalles del cambio']) !!}

                </div>

                <a href="/files" class="btn btn-secondary">Cancelar</a>
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