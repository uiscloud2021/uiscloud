@extends('adminlte::page')

@section('title', 'Directorios')

@section('content_header')
<h4 class="m-0">Editar Directorio</h4>
@stop


@section('content')
@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif
<div class="card">
        <div class="card-body">
            {!! Form::model($category, ['route' => ['categories.update', $category], 'method' => 'put']) !!}

                @include('category.form')

                <a href="/categories" class="btn btn-secondary">Cancelar</a>
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