@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h4 class="m-0">UIS Cloud</h4>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Servidor UIS</h1>
        </div>

        <div class="card-body">
        <div class="table-responsive">
        <div class="container-fluid">
            <div class="row">

                <div align="center" class="col-12">
                    <div id="category_chart"></div>
                </div>

                <div align="center" class="col-12">
                    <div id="files_chart"></div>
                </div>

                <div align="center" class="col-12">
                    <div id="modified_chart"></div>
                </div>

            </div>
        </div>
        </div>
        </div>
    </div>
@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
@stop

@section('js')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        //CODIGO PARA ARCHIVOS POR CATEGORIA
      var data = google.visualization.arrayToDataTable([
        ["Categoría", "Archivos", { role: "style" } ],

        @foreach ($categories as $category)
            ["{{ $category->categoria }}", {{ $category->archivos }}, 'color: #76A7FA'],
        @endforeach

      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" },
        2]);

      var options = {
        title: "Archivos actuales por categoría",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };

      var chart = new google.visualization.BarChart(document.getElementById("category_chart"));
      chart.draw(view, options);

      

      //CODIGO PARA ARCHIVOS ELIMINADOS POR CATEGORIA
      var data2 = google.visualization.arrayToDataTable([
        ["Categorías", "Archivos eliminados", { role: "style" } ],
        
        @foreach ($files as $file)
            ["{{ $file->categoria }}", {{ $file->eliminados }}, 'gold'],
        @endforeach

      ]);

      var view2 = new google.visualization.DataView(data2);
      view2.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" },
        2]);

      var options2 = {
        title: "Archivos eliminados por categoría",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };

      var chart2 = new google.visualization.BarChart(document.getElementById("files_chart"));
      chart2.draw(view2, options2);



      //CODIGO PARA ARCHIVOS MODIFICADOS POR CATEGORIA
      var data3 = google.visualization.arrayToDataTable([
        ["Categorías", "Archivos modificados", { role: "style" } ],

        @foreach ($logs as $log)
            ["{{ $log->categoria }}", {{ $log->modificados }}, 'silver'],
        @endforeach

      ]);

      var view3 = new google.visualization.DataView(data3);
      view3.setColumns([0, 1,
        { calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation" },
        2]);

      var options3 = {
        title: "Archivos modificados por categoría",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };

      var chart3 = new google.visualization.BarChart(document.getElementById("modified_chart"));
      chart3.draw(view3, options3);

    }
    </script>
@stop