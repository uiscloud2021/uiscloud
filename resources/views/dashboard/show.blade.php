@extends('adminlte::page')

@section('title', 'UIS-Cloud')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a href="/dashboard"> Servidor UIS</a></li>
              <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$category_name}}</a></li>

              @if($url_folder != "")
                <?php
                $a=0;
                $url_cat = explode("/", $url_folder);
                ?>
                @for($i=2; $i<=count($url_cat); $i++)
                    <?php
                    $a++;
                    ?> 
                    <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$url_cat[$a]}}</a></li>   
                @endfor
              @else
                <?php
                $url_cat="";
                ?>
                <li class="breadcrumb-item" style="font-size:16px; font-weight:bold;"><a> {{$url_folder}}</a></li>
              @endif

            </ol>
        </div><!-- /.col -->
        <div class="col-sm-6"></div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success"><strong>{{session('info')}}</strong></div>
@endif

<div class="card">
    <div class="card-header">
        
        @if($folder_name == "")
        <h4 class="card-title"><i class="far fa-folder"></i> {{$category_name}}</h4>
        @else
        <h4 class="card-title"><i class="far fa-folder"></i> {{$folder_name}}</h4>
        @endif
    
        <div class="card-tools" >
            <a style="font-size:15px; display:none;" href="#" id="downloadb" onclick="Comprimir();"><i class="fas fa-download"> Descargar zip</i></a>
            <a style="font-size:15px; display:none;" href="#" id="downloadone" onclick="DownloadB();"><i class="fas fa-download"> Descargar</i></a>
            <a style="padding-left: 15px; font-size:15px; display:none;" id="editb" href="#" onclick="EditB();"><i class="fas fa-pencil-alt"> Editar</i></a>
            <a style="padding-left: 15px; font-size:15px; display:none;" id="deleteb" href="#" onclick="DeleteB();"><i class="fas fa-trash"> Eliminar</i></a>
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i id="view2" class="fas fa-cogs"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="CreateFolder();"><i class="fas fa-plus-square"> Crear carpeta</i></a>    
                        <a class="dropdown-item" href="#" onclick="CreateFile();"><i class="fas fa-plus"> Cargar un archivo</i></a>
                        <a class="dropdown-item" href="#" onclick="CreateZIP();"><i class="fas fa-file-upload"> Cargar varios archivos</i></a>
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
    <div class="overlay-wrapper col-md-12">
        <div class="overlay" id="overlay" style="position:fixed; display:none">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2"> Subiendo...</div>
        </div>
        <div  id="icons">
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

                <div id="icon">
                    <div class="row">
                    @foreach ($folders as $folder)
                        <?php
                        $cont1++;
                        ?>

                        <div class="col-md-3" style="padding: 1em; text-align:center;">
                            <button onclick="DashSubmit('{{$folder->id}}');" style="background-color: Transparent; border: none; outline:none;" type="button">
                                <img width="80%" height="80%" src="vendor/adminlte/dist/img/icons/folder{{$folder->contenido}}.png">
                            </button>
                            <input style="vertical-align: top; height: 1em; width:17px; display:none;" type="checkbox" name="chk_folder{{$cont1}}" id="chk_folder{{$cont1}}" value="{{ $folder->id }}"/>
                            <br/>
                            <h5>{{$folder->name}}</h5>
                            <h6>{{date("d-M-y", strtotime($folder->created_at))}}</h6>
                        </div>
                    @endforeach
                    </div>
                </div>

                <div>
                    <div class="row">
                    @foreach ($files as $file)
                        <?php
                        $cont++;
                        $icono = strtolower($file->type);
                        ?>

                        <div class="col-md-3" style="padding: 1em; text-align:center;">
                            <a href="#" onclick="DescargarFile({{ $file->id }});">
                                <img src="vendor/adminlte/dist/img/icons/{{$icono}}.png" width="30%" heigth="30%">
                            </a>
                            <input style="vertical-align: top; height: 1em; width:17px;" type="checkbox" name="chk_icons{{$cont}}" id="chk_icons{{$cont}}" value="{{ $file->id }}"/>
                            <br/>
                            <h5 style="padding-top:7px;">{{$file->name}}.{{$file->type}}</h5>
                            <h6>{{date("d-M-y", strtotime($file->created_at))}}</h6>
                        </div>
                    @endforeach
                    </div>
                    <input type="hidden" value="{{$cont1}}" id="cont_folder">
                    <input type="hidden" value="{{$cont}}" id="cont_icons">
                </div>
            </form>
        </div>


        <div class="table-responsive" id="lists" style="display:none">
            <table id="list" class="table table-striped shadow-lg mt-4" style="width:100%;">
                <thead>
                    <tr>
                        <th scope="col" width="5%"></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Versión</th>
                        <th scope="col">Modificado</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
            </table>
        </div>
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
