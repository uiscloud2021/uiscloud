@extends('adminlte::page')

@section('title', 'Archivos')

@section('content_header')
<h4 class="m-0">Archivos descargados</h4>
@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif

<div class="card">

<div class="card-body">
<div class="table-responsive">
<table id="downloads" class="table table-striped shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col" width="5%"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Versión</th>
            <th scope="col">Modificado</th>
            <th scope="col">Usuario que lo tiene bloqueado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list_files as $file)
        <?php
        $icono = strtolower($file->type);
        ?>
            <tr>
                <td><a><img src="vendor/adminlte/dist/img/icons/{{$icono}}.png" style="text-align:center;" width="70%" heigth="70%"></a></td>
                <td><h5>{{ $file->name }}.{{ $file->type }}</h5></td>
                <td><h6>{{ $file->version }}</h6></td>
                <td><h6>{{ date("F j, Y, g:i a", strtotime($file->created_at->__toString())) }}</h6></td>
                <td><h6>{{ $file->user_block }}</h6></td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</div>

@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#downloads').DataTable({
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
            "language": espanol
        });
    } );

    let espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
    };
    </script>
@stop