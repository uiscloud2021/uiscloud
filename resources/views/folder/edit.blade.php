@extends('adminlte::page')

@section('title', 'Carpetas')

@section('content_header')
<h4 class="m-0">Editar Carpeta</h4>
@stop


@section('content')
@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif
<div class="card">
        <div class="card-body">
            {!! Form::model($folder, ['route' => ['folders.update', $folder], 'method' => 'put']) !!}

                @include('folder.form')

                <a href="/folders" class="btn btn-secondary">Cancelar</a>
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