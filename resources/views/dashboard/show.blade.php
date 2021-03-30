@extends('adminlte::page')

@section('title', 'UIS-Cloud')

@section('content_header')
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a href="/dashboard"> Servidor UIS</a></li>
              <li class="breadcrumb-item active" style="font-size:16px; font-weight:bold;"><a> {{$category_name}}</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            
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
        <h5 class="card-title">{{$category_name}}</h5>

        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-tool">
                    <i class="fas fa-plus"> Nuevo archivo</i>
                </button>
                <button type="button" class="btn btn-tool">
                    <i class="fas fa-plus-square"> Nueva carpeta</i>
                </button>
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

<div id="icons">
<table id="icon" class="table" style="width:100%">
    <tbody>
        <tr>
            @foreach ($files as $file)
                <td><a href="{{$file->url}}"><img src="vendor/adminlte/dist/img/icons/{{$file->type}}.png" width="10%" heigth="10%"><br/>
                {{ $file->name }}</a></td>
            @endforeach
        </tr>
    </tbody>
</table>
</div>




<div id="lists" style="display:none">
<table id="list" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Fecha de modificación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
            <tr>
                <td width="7%"><img src="vendor/adminlte/dist/img/icons/{{ $file->type }}.png" width="70%" heigth="70%"></td>
                <td><a href="{{$file->url}}">{{ $file->name }}</a></td>
                <td>{{ $file->created_at }}</td>
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
        $("#view").removeClass("fas fa-table");
        $("#view").addClass("fas fa-list");
    }

    function Icon(){
        $('#lists').hide(500);
        $('#icons').show(1500);
        $("#view").removeClass("fas fa-list");
        $("#view").addClass("fas fa-table");
    }
    </script>
@stop
