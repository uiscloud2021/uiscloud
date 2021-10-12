@extends('adminlte::page')

@section('title', 'Notificaciones')

@section('content_header')
<h4 class="m-0">Archivos que faltan de actualizar</h4>
@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif

<div class="card">

<div class="card-body">
<div class="table-responsive">
<table id="list_notification" class="table table-striped shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col" width="5%"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Versión</th>
            <th scope="col">Modificado</th>
            <th scope="col"></th>
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
                <td><a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFile({{$file->id}})">Actualizar</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</div>

<!--MODALS-->
@include('dashboard.modals')

@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="{{ asset('js/servidor.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('#list_notification').DataTable({
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
            "language": espanol
        });
    } );

    </script>
@stop