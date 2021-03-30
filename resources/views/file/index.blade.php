@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
<h4 class="m-0">Lista de Archivos</h4>
@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif

<div class="card">

<div class="card-header">
    <a href="{{route('files.create')}}" class="btn btn-secondary">Agregar archivo</a> 
</div>

<div class="card-body">
<table id="files" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Tipo de archivo</th>
            <th scope="col">Fecha</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
            <tr>
                <td width="7%"><img src="vendor/adminlte/dist/img/icons/{{$file->type}}.png" width="90%" heigth="90%"></td>
                <td><a target="_blank" href="{{$file->url}}">{{ $file->name }}</a></td>
                <td>{{ $file->type }}</td>
                <td>{{ $file->created_at }}</td>
                <td width="10px"><a class="btn btn-info btn-sm" href="{{route('files.edit', $file)}}">Editar</a></td>
                <td width="10px">
                <form action="{{route('files.destroy',$file)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#files').DataTable({
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]]
        });
    } );
    </script>
@stop