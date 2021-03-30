@extends('adminlte::page')

@section('title', 'Papeleria de reciclaje')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0">Archivos en papelera</h4>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div>

@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title"></h5>

        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i id="view" class="fas fa-table"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(46px, 19px, 0px);">
                    <a href="#" class="dropdown-item" onclick="Icon();"><i class="fas fa-table"> Iconos</i></a>
                    <a href="#" class="dropdown-item" onclick="List();"><i class="fas fa-list"> Lista</i></a>
                    <a class="dropdown-divider"></a>
                </div>
                
            </div>
        </div>
    </div>

<div class="card-body">

<table id="icons" class="table" style="width:100%">
    <tbody>
        <tr>
            @foreach ($recycleds as $recycled)
            
                <td><a target="_blank" href="{{$recycled->url_new}}"><img src="vendor/adminlte/dist/img/icons/{{$recycled->type}}.png" width="10%" heigth="10%"><br/>
                {{ $recycled->name }}.{{ $recycled->type }}</a></td>
            
            @endforeach
        </tr>
    </tbody>
</table>

<div id="lists" style="display:none">
<table id="list" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Fecha de modificación</th>
            <th scope="col">Ruta anterior</th>
            <th scope="col">Usuario</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recycleds as $recycled)
            <tr>
                <td width="7%"><img src="vendor/adminlte/dist/img/icons/{{$recycled->type}}.png" width="90%" heigth="90%"></td>
                <td><a target="_blank" href="{{$recycled->url_new}}">{{ $recycled->name }}</a></td>
                <td>{{ $recycled->created_at }}</td>
                <td>{{ $recycled->category }}</td>
                <td>{{ $recycled->user }}</td>
                <td width="10px"><a class="btn btn-info btn-sm" target="_blank" href="{{$recycled->url_new}}">Descargar</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

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
        $('#list').DataTable({
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]]
        });
    } );

    function List(){
        $('#icons').hide(500);
        $('#lists').show(1500);
    }

    function Icon(){
        $('#lists').hide(500);
        $('#icons').show(1500);
    }
    </script>
@stop