<?php
    session_start();
    $sessaoUsuario = $_SESSION["user"];
    $sessaoSenha = $_SESSION["password"];
    if (!isset($sessaoUsuario) || !isset($sessaoSenha)) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        
        <title>Relatórios</title>
            
        <link href="css/bootstrap.css" rel="stylesheet">

        <link href="css/site.css" rel="stylesheet">

        <link href="leaflet/leaflet.css" rel="stylesheet">

        <script src="leaflet/leaflet.js"></script>        

        <script src = "js/leaflet-heat.js"></script>

        <style>

            #w0{
                background-color: #0E2F44;
            }
            #w1{
                background-color: #E4E9ED;
            }
            #mapa { 
                height: 350px; 
            }
            #csv,#pdf{
                margin-top: 25px;
            }
            .form-data{
                  display: block;
                  width: 100%;
                  height: 35px;
                  padding: 6px 12px;
                  font-size: 14px;
                  line-height: 1.42857143;
                  color: #555;
                  background-color: #fff;
                  background-image: none;
                  border: 1px solid #ccc;
                  border-radius: 4px;
                  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            }

        </style>

    </head>

    <body>
    
        <div class="wrap">
            <nav id="w0" class="navbar-inverse navbar-fixed-top navbar" role="navigation">
                <div class="container">

                    <!-- Div usada para categorizar as ocorrencias -->
                    <div id="painel-preencher-ocorrencia"> </div>

                    <div id = "w0-collapse" class ="collapse navbar-collapse">
                        <ul class="navbar-nav navbar-center nav">
                            <li>
                                <a href="configuracoes.php"> <img src= "img/conf.png"> </a>
                            </li>                           
                            <li>
                                <a href="#"> <img src = "img/report.png"> </a>
                            </li>
                            <li>
                                <a href="logout.php"> <img src="img/exit.png"> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="container">
                
                <h2> Alertas por Período </h2>
                
                <div class="container-preencher-ocorrencia">
                    
                    <form id="w1" action="buildCSVReport.php" method="post">

                        <div class='row'>

                            <div class='col-md-4'>

                                <div class="form-group field-relatorioalertasporperiodo-datainicio required">

                                    <label class="control-label" for="relatorioalertasporperiodo-datainicio">
                                        Data de Início
                                    </label>

                                    <input type="text" id="relatorioalertasporperiodo-datainicio" class = "form-data"name="dataInicio" placeholder="Selecione a data de início">

                                    <div class="help-block"> </div>

                                </div>

                            </div>

                            <div class='col-md-4'>

                                <div class="form-group field-relatorioalertasporperiodo-datafim required">

                                    <label class="control-label" for="relatorioalertasporperiodo-datafim">
                                        Data de Fim
                                    </label>

                                    <input type="text" id="relatorioalertasporperiodo-datafim" class = "form-data" name="dataFim" placeholder="Selecione a data de fim">

                                    <div class="help-block"> </div>

                                </div>

                            </div>

                            <button id = "csv" class="btn btn-primary" onclick = "updateMap()"> Gerar relatório .csv</button>

                        </div>

                    </form>                        
                        
                </div>
<!--                
                <br>
                
                <p> <b> Exibindo: </b> 0 de 0 alertas </p>

                <table id = "tableAlertas" class="table tabela">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Pessoa</th>
                            <th>Vínculo</th>
                            <th>Telefone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr> 
                            <td colspan='8'> Não existem alertas a serem exibidos. </td>
                        </tr>        
                        <tr>
                            <td colspan='1'> 1 </td> 
                            <td colspan='1'> 03/05/1995 </td> 
                            <td colspan='1'> 05:49:23 </td> 
                            <td colspan='1'> Daniel </td> 
                            <td colspan='1'> Funcionario </td> 
                            <td colspan='1'> 997807925 </td> 
                            <td colspan='1'> atendido </td> 
                        <tr>
                    </tbody>
                </table>
                
                <div class="btn-group" role="group">

                    <div class="btn-group">
                        <button id="w2" class="btn btn-default dropdown-toggle" title="Export data in selected format" data-toggle="dropdown"><i class="glyphicon glyphicon-export"></i> <span class="caret"></span></button>

                        <ul id="w3" class="dropdown-menu">

                            <li title="Comma Separated Values">
                                <a id="w1-csv" class="export-full-csv" href="#" data-format="CSV" tabindex="-1">
                                    <i class="text-primary glyphicon glyphicon-floppy-open"> </i> CSV 
                                </a> 
                            </li>

                            <li title="Portable Document Format">
                                <a id="w1-pdf" class="export-full-pdf" href="#" data-format="PDF" tabindex="-1">
                                    <i class="text-danger glyphicon glyphicon-floppy-disk"> </i> PDF
                                </a>
                            </li>
                        </ul>
                    </div>

                    <form id="w1-form" class="kv-export-full-form" action="/web/index.php?r=relatorio%2Falertas-por-periodo" method="post" target="_self">

                        <input type="hidden" name="_csrf" value="enpyaUFQdVNDSx0AFyUvOh8ZI1o0KiEcDD44UQogMj8xA18rJTomMg==">

                        <input type="hidden" name="export_type" value="Excel2007">

                        <input type="hidden" name="exportFull_w1" value="1">

                        <input type="hidden" name="export_columns" value="">

                        <input type="hidden" name="column_selector_enabled" value="1">
                    </form>
                </div> 
                  -->
                <h2> Mapa de calor </h2>

                <div id = "mapa"> </div>

                <script >

                    function updateMap(){

                        var osmMapUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

                        var osmAttrib ='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>';

                        var osMap = L.tileLayer(osmMapUrl, {minZoom: 15, maxZoom: 19, attribution: osmAttrib});

                        var dataInicio = null;

                        var dataFim = null;

                        var markersArray = [];

                        jQuery(function($){

                            dataInicio = $("#relatorioalertasporperiodo-datainicio").val();
                            dataFim = $("#relatorioalertasporperiodo-datafim").val();

                        });

                        console.log("data: " + dataFim);

                        if (dataInicio == "" && dataFim == "" ){

                            return;
                        }

                        var mapa = L.map('mapa', {layers: [osMap], zoomControl: false}).setView([-20.277158, -40.303028], 15);                               
                        
                        $.get("getHeatMapMarkers.php", 
                            { 
                                dataInicio: dataInicio, 
                                dataFim: dataFim

                            }, function(data){

                                if ( data.length == 0 ) {

                                    console.log("Sem dados no Json recebido de getHeatMapMarkers.php");
                                    return;
                                }
                                else {

                                    console.log("Json recebido" + data);
                                }

                                data = $.parseJSON(data);

                                for (var i = 0; i < data.length; i++){

                                    var marker = {
                                        lat: data[i].lat,
                                        lng: data[i].lng,
                                        count: 1
                                    };

                                    markersArray.push(marker);
                                }
                            }
                        );

                        var heat = L.heatLayer(markersArray, {radius: 25}).addTo(mapa);                        

                        }

                </script>

            </div> 
        </div>

        <script src="js/jquery.js"></script>
        <script src="js/yii.js"></script>
        <script src="js/yii.validation.js"></script>
        <script src="js/yii.activeForm.js"></script>
        <script src = "js/maskedInput.js"></script>
        <script>
            jQuery(document).ready(function () {
                jQuery('#w1').yiiActiveForm([{"id":"relatorioalertasporperiodo-datainicio","name":"dataInicio","container":".field-relatorioalertasporperiodo-datainicio","input":"#relatorioalertasporperiodo-datainicio","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Data de início deve ser preenchida"});}},{"id":"relatorioalertasporperiodo-datafim","name":"dataFim","container":".field-relatorioalertasporperiodo-datafim","input":"#relatorioalertasporperiodo-datafim","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Data de fim deve ser preenchida"});}}], []);
                });
        </script>
        <script>
        </script>        
        <script >
            jQuery(function($){
               $("#relatorioalertasporperiodo-datainicio").mask("99/99/9999");
                });
            jQuery(function($){
               $("#relatorioalertasporperiodo-datafim").mask("99/99/9999");
                });      
        </script>
    </body>
</html>
