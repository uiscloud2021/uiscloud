@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Inicio</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Bienvenidos a UIS CLOUD</h1>
        </div>

        <div class="card-body">
            
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$usercounter}}</h3>
                            <p>Usuarios registrados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <a href="{{route('users.index')}}" class="small-box-footer">Ver usuarios <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{$filecounter}}</h3>
                            <p>Archivos registrados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-archive"></i>
                        </div>
                        <a href="{{route('files.index')}}" class="small-box-footer">Ver archivos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> //console.log('Hi!'); </script>
@stop
