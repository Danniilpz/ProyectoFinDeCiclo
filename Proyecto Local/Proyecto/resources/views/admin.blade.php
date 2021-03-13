@extends('layouts.secondary')

@section('pageTitle','Panel de administración - Loopz')

@section('hojasCSS')
    <link rel="stylesheet" href="css/forms.css"/>
@endsection

@section('scripts')
    <script src="js/forms.js"></script>
    <script type="text/javascript" src="chart/js/fusioncharts.js"></script>
    <script type="text/javascript" src="chart/js/themes/fusioncharts.theme.fusion.js"></script>
@endsection

@section('content')
    <div class="container">
        <h2>Panel de administración</h2>
        <hr/>
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{Session::get('success')}}
            </div>
        @elseif(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {{Session::get('error')}}
            </div>
        @elseif(Session::has('warning'))
            <div class="alert alert-warning" role="alert">
                {{Session::get('warning')}}
            </div>
        @endif
    @php
        include("includes/fusioncharts.php");
                $arrChartConfig = array(
                  "chart" => array(
                    "caption" => "",
                    "subCaption" => "",
                    "xAxisName" => "Búsqueda",
                    "yAxisName" => "Número de veces",
                    "numberSuffix" => "",
                    "theme" => "fusion"
                    )
                );

              // An array of hash objects which stores data
              $arrChartData = $datos;

                /*
                $arrChartData = array(
                ["Venezuela", "290"],
                ["Saudi", "260"],
                ["Canada", "180"],
                ["Iran", "140"],
                ["Russia", "115"],
                ["UAE", "100"],
                ["US", "30"],
                ["China", "30"]
              );
              */
              $arrLabelValueData = array();

            // Pushing labels and values
            for($i = 0; $i < count($arrChartData); $i++) {
                array_push($arrLabelValueData, array(
                    "label" => $arrChartData[$i][0], "value" => $arrChartData[$i][1]
                ));
            }

        $arrChartConfig["data"] = $arrLabelValueData;

        // JSON Encode the data to retrieve the string containing the JSON representation of the data in the array.
        $jsonEncodedData = json_encode($arrChartConfig);

        // chart object
        $Chart = new FusionCharts("column2d", "MyFirstChart" , "80%", "350", "chart-container", "json", $jsonEncodedData);

        // Render the chart
        $Chart->render();
    @endphp
        <div class="form-group card">
            <div class="card-body" id="datosbbdd">
                <div class="row text-center">
                    <div class="col-sm-3 col-6">
                        <h2>{{$contkeywords}}</h2> keywords
                    </div>
                    <div class="col-sm-3 col-6">
                        <h2>{{$contdirecciones}}</h2> direcciones
                    </div>
                    <div class="col-sm-3 col-6">
                        <h2>{{$contsitios}}</h2> sitios
                    </div>
                    <div class="col-sm-3 col-6">
                        <h2>{{$contusers}}</h2> usuarios
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group card">
            <div class="card-header">
                <h4 class="text-center">Búsquedas más frecuentes en Loopz</h4>
            </div>
            <div class="card-body">
                <div id="chart-container" class="col-12 d-flex justify-content-center"></div>
            </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4 col-sm-6 col-12 card {{($estadorobot?'bg-success':'bg-danger')}}">
              <div class="card-header">
                  <h4 class="text-center">Estado del robot</h4>
              </div>
              <div class="card-body text-center">
                  <h4 class="">{{($estadorobot?'En ejecución':'Detenido')}}</h4>
                  <a
                    @if($estadorobot)
                      data-toggle="modal" data-target="#modalRobot"
                    @else
                      href="robotstart"
                    @endif
                    class="col-sm-6 col-10 text-light btn {{($estadorobot?'btn-danger':'btn-success')}}">{{($estadorobot?'Detener':'Reanudar')}}</a>
              </div>
              @if($estadorobot)
                @include("modalrobot")
              @endif
          </div>
          <div class="form-group col-md-4 col-12 card">
              <div class="card-header">
                  <h4 class="text-center">Tareas administrativas</h4>
              </div>
              <div class="card-body">
                  <a href="listadousuarios" class="col-12 btn btn-warning">Gestión de usuarios</a>
                  <a href="{{ route('historialcompleto') }}" class="col-12 btn btn-info mt-2">Ver historial completo</a>
              </div>
          </div>
            <div class="form-group col-md-4 col-sm-6 col-12 card">
                <div class="card-header">
                    <h4 class="text-center">Mantenimiento</h4>
                </div>
                <div class="card-body">
                    <a href="optimizar" class="col-12 btn btn-success">Optimizar base de datos</a>
                </div>
            </div>
        </div>

    </div>
@endsection
