@extends('adminlte::page')

@section('title', 'Servidor UIS')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item active" style="font-size:16px; font-weight:bold;"><a> Servidor UIS</a></li>
            </ol>
        </div><!-- /.col -->
        <div class="col-sm-6"></div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')


<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="far fa-folder"></i> Mis directorios</h5>
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

        <div id="icons">
            <form action="{{route('dashboard')}}" id="form_directorios" method="POST">
                @csrf
                <input type="hidden" name="id_category" id="category_id">
                <input type="hidden" value="0" name="nivel_folder">
                <div class="row" id="icon">
                    @foreach ($categories as $category)
                        <?php
                        $icono = strtolower($category->contenido);
                        ?>

                        <div class="col-md-3" style="padding: 1em; text-align:center;">
                            <button onclick="Directorios('{{$category->id}}');" style="background-color: Transparent; border: none; outline:none;" type="button">
                            <img width="80%" heigth="80%" src="vendor/adminlte/dist/img/icons/folder{{$icono}}.png" >
                            </button><br/>
                            <h5>{{$category->name}}</h5>
                            <h6>{{date("d-M-y", strtotime($category->created_at))}}</h6>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>


        <div class="table-responsive" id="lists" style="display:none">
            <table id="list" class="table table-striped shadow-lg mt-4" style="width:100%;">
                <thead >
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Modificado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td width="5%"><img src="vendor/adminlte/dist/img/icons/folder{{ $category->contenido }}.png" width="95%" heigth="95%"></td>
                            <form action="{{route('dashboard')}}" method="POST">
                                @csrf
                                <input type="hidden" value="{{$category->id}}" name="id_category">
                                <input type="hidden" value="{{$category->name}}" name="name_category"> 
                                <input type="hidden" value="0" name="nivel_folder">   
                                <td><button style="background-color: Transparent; border: none; outline:none;" type="submit"><h5>{{ $category->name }}</h5></button></td>
                            </form>
                            <td><h6>{{date("F j, Y, g:i a", strtotime($category->created_at))}}</h6></td>
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
        $('#list').DataTable({
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
            "language": espanol
        });
    } );

    function Directorios(id_category){
        $('#category_id').val(id_category);
        $('#form_directorios').submit();
    }

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
