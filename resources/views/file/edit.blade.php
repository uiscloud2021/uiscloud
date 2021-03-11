@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
    <h1>Editar archivo</h1>
@stop

@section('content')
    <form action="/files/{{$file->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{$file->name}}" tabindex="1">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Tamaño</label>
            <input type="text" class="form-control" id="tamano" name="tamano" value="{{$file->size}}" tabindex="2">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Tipo</label>
            <input type="text" class="form-control" id="tipo" name="tipo" value="{{$file->type}}" tabindex="3">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Carpeta</label>
            <input type="text" class="form-control" id="carpeta" name="carpeta" value="{{$file->folder}}" tabindex="4">
        </div>
        <a href="/files" class="btn btn-secondary" tabindex="6">Cancelar</a> 
        <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    
@stop