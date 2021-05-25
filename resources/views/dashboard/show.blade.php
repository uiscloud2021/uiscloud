@extends('adminlte::page')

@section('title', 'UIS-Cloud')

@section('content_header')
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a href="/dashboard"> Servidor UIS</a></li>
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$category_name}}</a></li>
              <?php
              if($url_folder != ""){
                $a=0;
                $url_cat = explode("/", $url_folder);
                for($i=2; $i<=count($url_cat); $i++){
                    $a++;
              ?>
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$url_cat[$a]}}</a></li>
              <?php
                }
              }else{
                $url_cat="";
              ?>
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$url_folder}}</a></li>
              <?php
              }
              ?>
              
              
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
    <?php
        if($folder_name == ""){
    ?>
        <h5 class="card-title">{{$category_name}}</h5>
    <?php
        } else{
    ?>
        <h5 class="card-title">{{$folder_name}}</h5>
    <?php
        }
    ?>

        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-tool" onclick="CreateFile();">
                    <i class="fas fa-plus"> Nuevo archivo</i>
                </button>
                <button type="button" class="btn btn-tool" onclick="CreateFolder();">
                    <i class="fas fa-plus-square"> Nueva carpeta</i>
                </button>
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i id="view" class="fas fa-table"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(46px, 19px, 0px);">
                    <a href="#" class="dropdown-item" onclick="Icon();"><i class="fas fa-table"> Iconos</i></a>
                    <a href="#" class="dropdown-item" onclick="List();"><i class="fas fa-list"> Lista</i></a>
                    <a class="dropdown-divider"></a>
                    <input type="hidden" value="{{$url_folder}}" id="urlppal_folder">
                    <input type="hidden" value="{{$folder_id}}" id="idppal_folder"> 
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
        <form method="POST" action="{{route('dashboard')}}" id="form_details" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$category_id}}" name="id_category" id="id_category">
            <input type="hidden" value="{{$category_name}}" name="name_category">
            <input type="hidden" value="{{$nivel_id}}" name="nivel_folder" id="nivel_folder">
            @foreach ($folders as $folder)
                    <input type="hidden" value="{{$folder->id}}" name="id_folder" id="id_folder">
                    <input type="hidden" value="{{$folder->url}}" name="url_folder" id="url_folder">
                <?php
                $cont++;
                if($cont % 7 == 0){
                    echo '<tr></tr>';
                }
                ?>
                <td><input style="vertical-align: middle; float: left;" type="radio" name="radio_details" value="{{ $folder->id }}" onclick="FolderDetails(this.value);"/>
                    <button onclick="DashSubmit('{{$folder->id}}');" style="background-color: Transparent; border: none; outline:none;" type="button">
                    <img style="vertical-align: middle; float: left;" src="vendor/adminlte/dist/img/icons/folder{{$folder->contenido}}.png" width="60%" heigth="60%">
                    </button><br/>
                    <span style="vertical-align: middle; float: left;">{{$folder->name}}</span>
                </td>
            @endforeach
            <tr></tr>
            @foreach ($files as $file)
                <?php
                $cont++;
                if($cont % 5 == 0){
                    echo '<tr></tr>';
                }
                ?>
                <td><input style="vertical-align: middle; float: left;" type="radio" name="radio_details" value="{{ $file->id }}" onclick="Details(this.value);"/>
                    <a href="#" onclick="DescargarFile({{ $file->id }});">
                    <img style="vertical-align: middle; float: left;" src="vendor/adminlte/dist/img/icons/{{$file->type}}.png" width="18%" heigth="18%">
                    <br/><br/>{{$file->name}}</a>
                    
                </td>
            @endforeach
        </form>
    </tbody>
</table>
</div>




<div class="table-responsive" id="lists" style="display:none">
<table id="list" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">Nombre del archivo</th>
            <th scope="col">Tipo</th>
            <th scope="col">Versión</th>
            <th scope="col">Fecha de modificación</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    
</table>
</div>



</div>

</div><!--FIN CONTAINER-->

<br/>
<div class="card" id="details" style="display:none">
    <div class="card-header">
        <h5 class="card-title">Detalles de archivo. Versiones anteriores</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="detail" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Nombre del archivo</th>
                        <th scope="col">Detalles</th>
                        <th scope="col">Versión</th>
                        <th scope="col">Fecha de modificación</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="card" id="folderdetails" style="display:none">
    <div class="card-header">
        <h5 class="card-title">Detalles de carpeta</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="details_folder" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%;">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Nombre de carpeta</th>
                        <th scope="col">Fecha de modificación</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody></tbody>
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

@stop
