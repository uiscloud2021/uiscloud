@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Nuevo usuario</h1>
@stop

@section('content')
    <form action="/users" method="POST">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" tabindex="1">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Correo electrónico</label>
            <input type="text" class="form-control" id="email" name="email" tabindex="2">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" tabindex="3">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Rol</label>
            <input type="text" class="form-control" id="rol" name="rol" tabindex="4">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" tabindex="5">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" tabindex="6">
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