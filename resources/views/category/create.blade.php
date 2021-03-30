@extends('adminlte::page')

@section('title', 'Directorios')

@section('content_header')
<h4 class="m-0">Nuevo Directorio</h4>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'categories.store', 'autocomplete' => 'off']) !!}

                @include('category.form')

                <a href="/categories" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Guardar directorio', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop