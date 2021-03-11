@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Editar usuario</h1>
@stop

@section('content')
    <form action="/users/{{$user->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{$user->name}}" tabindex="1">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Correo electrónico</label>
            <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}" tabindex="2">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Rol</label>
            <input type="text" class="form-control" id="rol" name="rol" value="{{$user->role}}" tabindex="3">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" value="{{$user->position}}" tabindex="4">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{$user->phone}}" tabindex="5">
        </div>
        <a href="/users" class="btn btn-secondary" tabindex="6">Cancelar</a> 
        <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    
@stop