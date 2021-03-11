@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
    <h1>Listado de Archivos</h1>
@stop

@section('content')
<a href="files/create" class="btn btn-primary mb-3">Nuevo</a> 

<table id="files" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Tamaño</th>
            <th scope="col">Tipo</th>
            <th scope="col">Carpeta</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td>{{ $file->name }}</td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->type }}</td>
                <td>{{ $file->folder }}</td>
                <td>
                <form action="{{route('files.destroy',$file->id)}}" method="POST">
                    <a href="/files/{{$file->id}}/edit" class="btn btn-info">Editar</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
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