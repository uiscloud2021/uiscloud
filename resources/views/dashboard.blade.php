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
          <div class="col-sm-6">
            
          </div><!-- /.col -->
        </div><!-- /.row -->
</div>
@stop

@section('content')


<div class="card">
    <div class="card-header">
        <h5 class="card-title">Servidor UIS</h5>

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

<div class="table-responsive" id="icons">
<table id="icon" class="table" style="width:100%">
<?php
    $cont=0;
?>
    <tbody>
            @foreach ($categories as $category)
                <form action="{{route('dashboard')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$category->id}}" name="id_category">
                    <input type="hidden" value="{{$category->name}}" name="name_category">
                    <input type="hidden" value="0" name="nivel_folder">
                    <?php
                    $cont++;
                    if($cont % 7 == 0){
                        echo '<tr></tr>';
                    }
                    ?>
                    <td><button style="background-color: Transparent; border: none; outline:none;" type="submit">
                        <img style="vertical-align: middle; float: left;" src="vendor/adminlte/dist/img/icons/folder{{$category->contenido}}.png" width="60%" heigth="60%">
                        </button><br/>
                        <span style="vertical-align: middle; float: left;">{{$category->name}}</span>
                    </td>
                
                </form>
            @endforeach
    </tbody>
</table>
</div>




<div class="table-responsive" id="lists" style="display:none">
<table id="list" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col"></th>
            <th scope="col">Directorios</th>
            <th scope="col">Fecha de modificación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                 <td width="7%"><img src="vendor/adminlte/dist/img/icons/folder{{ $category->contenido }}.png" width="50%" heigth="50%"></td>
                
                <form action="{{route('dashboard')}}" method="POST">
                    @csrf
                    <input type="text" value="{{$category->id}}" name="id_category">
                    <input type="hidden" value="{{$category->name}}" name="name_category"> 
                    <input type="hidden" value="0" name="nivel_folder">   
                    
                    <td><button style="background-color: Transparent; border: none; outline:none;" type="submit">{{ $category->name }}</button></td>
                </form>
            
                <td>{{ $category->created_at }}</td>
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