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
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i id="view" class="fas fa-cogs"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="CreateFile();"><i class="fas fa-plus"> Cargar archivo</i></a>
                        <a class="dropdown-item" href="#" onclick="CreateFolder();"><i class="fas fa-plus-square"> Crear carpeta</i></a>
                        <a class="dropdown-item" href="#" onclick="CreateZIP();"><i class="fas fa-file-archive"> Cargar carpeta zip</i></a>
                        <a class="dropdown-item" href="#" id="seleccionar" onclick="Seleccionar();"><i class="fas fa-check-square"> Seleccionar archivos</i></a>
                        <a class="dropdown-item" href="#" style="display:none" id="comprimir" onclick="Comprimir();"><i class="fas fa-download"> Descargar zip</i></a>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i id="view" class="fas fa-table"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(46px, 19px, 0px);">
                    <a href="#" class="dropdown-item" onclick="Icon();"><i class="fas fa-table"> Iconos</i></a>
                    <a href="#" class="dropdown-item" onclick="List();"><i class="fas fa-list"> Lista</i></a>
                    <a class="dropdown-divider"></a>
                    <input type="hidden" value="{{$url_folder}}" id="urlppal_folder" name="urlppal_folder">
                    <input type="hidden" value="{{$folder_id}}" id="idppal_folder" name="idppal_folder"> 
                </div>
            </div>
            
        </div>
    </div>

<div class="card-body">

<div class="table-responsive" id="icons">

<?php
    $cont1=0;
    $cont=0;
?>
    
        <form method="POST" action="{{route('dashboard')}}" id="form_details" autocomplete="off">
            @csrf
            <input type="hidden" value="{{$category_id}}" name="id_category" id="id_category">
            <input type="hidden" value="{{$category_name}}" name="name_category">
            <input type="hidden" value="{{$nivel_id}}" name="nivel_folder" id="nivel_folder">
            <input type="hidden" name="id_folder" id="id_folder">
            <input type="hidden" name="url_folder" id="url_folder"> 

            <table id="icon" class="table" style="width:100%">
            <tbody>
            @foreach ($folders as $folder)
                <?php
                if($cont1 % 5 == 0){
                    echo '<tr></tr>';
                }
                $cont1++;
                ?>
                <td><input style="vertical-align: middle; float: left;" type="radio" name="radio_details" id="radiofolder_details<?php echo $cont1;?>" value="{{ $folder->id }}" onclick="FolderDetails(this.value);"/>
                <input style="vertical-align: middle; float: left; display:none;" type="checkbox" name="chk_folder<?php echo $cont1;?>" id="chk_folder<?php echo $cont1;?>" value="{{ $folder->id }}"/>
                    <button onclick="DashSubmit('{{$folder->id}}');" style="background-color: Transparent; border: none; outline:none;" type="button">
                    <img style="vertical-align: middle; float: left;" src="vendor/adminlte/dist/img/icons/folder{{$folder->contenido}}.png" width="50%" heigth="50%">
                    </button><br/>
                    <span style="vertical-align: middle; float: left;">{{$folder->name}}</span>
                </td>
            @endforeach
            </tbody>
            </table>
            <table  class="table" style="width:100%">
            <tbody>
            @foreach ($files as $file)
                <?php
                if($cont % 4 == 0){
                    echo '<tr></tr>';
                }
                $cont++;
                ?>
                <td><input style="vertical-align: middle; float: left;" type="radio" name="radio_details" id="radioicons_details<?php echo $cont;?>" value="{{ $file->id }}" onclick="Details(this.value);"/>
                    <input style="vertical-align: middle; float: left; display:none;" type="checkbox" name="chk_icons<?php echo $cont;?>" id="chk_icons<?php echo $cont;?>" value="{{ $file->id }}"/>
                    <a href="#" onclick="DescargarFile({{ $file->id }});">
                    <img style="vertical-align: middle; float: left;" src="vendor/adminlte/dist/img/icons/{{$file->type}}.png" width="19%" heigth="19%">
                    <br/><br/>{{$file->name}}</a>
                    
                </td>
            @endforeach
            <input type="hidden" value="{{$cont1}}" id="cont_folder">
            <input type="hidden" value="{{$cont}}" id="cont_icons">
            </tbody>
            </table>
        </form>
    
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
                        <th scope="col">Usuario</th>
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
