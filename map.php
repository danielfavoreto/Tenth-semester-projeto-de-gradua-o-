<?php
	$host = 'alertaUfes';
	$user = 'root';
	$pass = '';
	$database_name = 'ufes';
	$conexao = new mysqli($host, $user, $pass, $database_name);
	if ($conexao->connect_error) {
			trigger_error('Database connection failed: '  . $conexao->connect_error, E_USER_ERROR);
	}
?>
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
<html>
	<head>

		<script src="js/jquery.js"></script>

		<script src="leaflet/leaflet.js"></script>

		<script src = "leaflet/markercluster.js"></script>

		<script src = "https://unpkg.com/@mapbox/leaflet-pip@latest/leaflet-pip.js"></script>

		<script src = "leaflet/bouncingMarker/leaflet.smoothmarkerbouncing.js"></script>

		<script src= "leaflet/easy-button.js"></script>		

		<link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.0.0/src/easy-button.css">

		<link href="leaflet/leaflet.css" rel="stylesheet"/>

		<link href="leaflet/easy-button.css" rel="stylesheet">
    	
    	<link href="css/bootstrap.css" rel="stylesheet">
    	
    	<link href="css/site.css" rel="stylesheet">

    	<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/0.4.0/MarkerCluster.css" />

    	<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/0.4.0/MarkerCluster.Default.css" />

		<title>PG</title>

		<style>
			html, body, #map {
		      height:100%;
		      width:100%;
		      padding:0px;
		      margin:0px;
		   	}
		   	#w0{
		   		background-color: #0E2F44;
		   	}
		   	.popupCenter{
		   		text-align: center;
		   	}
		   	#container-preencher{
		   		color: #000;
		   		background-color: #000;
		   	}
		   	#w1{
		   		background-color: #fff;
		   	}
		   	label span { 
		   		color: #449D44;
		   	}
			#slidecontainer label {
			    display: block;
			    text-align: center;
			}		   	
		   	#slidecontainer {
			    width: 25%;
			}
			#tempoPermanencia{
				display: block;
				width: 100%;
				height: 17px;
				font-size: 14px;
				color: #0E2F44;
				background-color: #449D44;
				border-radius: 10px;	
				text-align: center;		
				font-weight: bold;	
			}
			.slider {
			    -webkit-appearance: none;
			    width: 100%;
			    height: 15px;
			    border-radius: 10px;   
			    background: #449D44;
			    outline: none;
			    opacity: 0.7;
			    -webkit-transition: .2s;
			    transition: opacity .2s;
			}

			.slider::-webkit-slider-thumb {
			    -webkit-appearance: none;
			    appearance: none;
			    width: 12px;
			    height: 12px;
			    border-radius: 50%; 
			    background: #0E2F44;
			    cursor: pointer;
			}

			.slider::-moz-range-thumb {
			    width: 25px;
			    height: 25px;
			    border-radius: 50%;
			    background: #0E2F44;
			    cursor: pointer;
			}

		</style>
		
	</head>

	<body>
		<div class = "wrap">
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
								<a href="relatorio.php"> <img src = "img/report.png"> </a>
							</li>
							<li>
								<a href="logout.php"> <img src="img/exit.png"> </a>
							</li>
						</ul>
		
						<!--<div id = "slidecontainer" class="navbar-nav navbar-right nav">

							<label> <span> Zoom do mapa </span> </label>							
						  	<input type="range" min="15" max="19" value="15" class="slider form-control" id="zoomRange">

						</div>	-->

						<!--<div class="navbar-nav navbar-left nav">

							<label> <span> Tempo de Permanência dos Marcadores (em horas) </span> </label>

							<input type="number" min = "1" class = "slider form-control" id="tempoPermanencia" value="1000" step = "1" onchange = "validateTempo()">

						</div>-->
					</div>
				</div>
			</nav>

			<div id = "map"> </div>

    		<!--<audio id="audio"><source src="audio/alert.mp3" type="audio/mp3" /></audio>-->

			<script>
		        var propriedadesStatus = {
		            0: {
		                iconUrl: 'img/novoAlerta.png',
		                label: '<span class="label label-primary">Novo</span>',
		                labelSimples: "Novo"
		            },
		            1: {
		                iconUrl: 'img/atendidoAlerta.png',
		                label: '<span class="label label-success">Atendido - Assalto</span>',
		                labelSimples: "Assalto"
		            },
		            2: {
		                iconUrl: 'img/atendidoAlerta.png',
		                label: '<span class="label label-success">Atendido - Acidente</span>',
		                labelSimples: "Acidente"
		            },
		            3: {
		                iconUrl: 'img/atendidoAlerta.png',
		                label: '<span class="label label-success">Atendido - Outros</span>',
		                labelSimples: "Outros"
		            },
		            4: {
		                iconUrl: 'img/atendidoAlerta.png',
		                label: '<span class="label label-success">Atendido - Falso</span>',
		                labelSimples: "Falso"
		            }
		        };

		        var selecionadoAssalto = false;

		        var selecionadoAcidente = false;

		        var selecionadoOutro = false;

		        var selecionadoFalso = false;

		        var tempoPermanenciaHoras = null;

				// Popup dos Markers
				var infoWindow = null;

				// A visibilidade do mapa precisa estar global
				var mapa = null;
				
			    // Array global dos marcadores presentes no mapa
				var markersArray = [];
			        
			    // Array temporário para comportar os ids de alertas atualizados
			    var markersArrayTemporario = [];

			    // MarkerCluster para fazer a categorização dos alertas
			    var markerClusters = null;

			    // UfesGeoJson para armazenar o geoJson do campus da ufes
			    var ufesGeoJson = null;

			    // LakeGeoJson para armazenar o geoJson do lago da ufes
			    var lakeGeoJson = null;

			    // Classe customizada de marcador
			    customMarker = L.Marker.extend({
			    	options: {
			    		id: '',
			    		lat: '',
			    		lng: '',
			    		data: '',
			    		hora: '',
			    		nomePessoa: '',
			    		telefonePessoa: '',
			    		status: '',
			    		loginPessoa: ''
			    	}
			    });

				var MarkerIcon = L.Icon.extend({
				    options: {
				        iconSize:     [28, 28],
				        iconAnchor:   [22, 20],
				        popupAnchor:  [-3, -30]
				    }
				});

		        // Cria o mapa e chama a função de atualização a cada intervalo definido em setInterval
				function inicializarMapa() {

					var osmSatelliteUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';

					var osmMapUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

					var osmAttrib ='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>';

					var osMap = L.tileLayer(osmMapUrl, {minZoom: 15, maxZoom: 19, attribution: osmAttrib});		

					var osmSatellite = L.tileLayer(osmSatelliteUrl, {minZoom: 15, maxZoom: 19, attribution: osmAttrib, subdomains: 'abcd', id: 'mapbox.satellite', accessToken: 'pk.eyJ1IjoiZGFuaWVsZmF2b3JldG8iLCJhIjoiY2o2cjJsc29kMDUwajMycGJ1NW02OGdxMSJ9.EAdQQ-HPcGKe6I0SYLEzOg'});

					var baseLayers = {"osMap": osMap, "Satellite": osmSatellite};

					mapa = L.map('map', {layers: [osMap], zoomControl: false}).setView([-20.277158, -40.303028], 15);

					var control = L.control.layers(baseLayers, null, {position: 'bottomleft'}).addTo(mapa);

					/*var zoomSlider = document.getElementById("zoomRange");

					this.value = mapa.getZoom();

					zoomSlider.oninput = function() {

						mapa.setZoom(this.value);

						this.value = mapa.getZoom();

						//var mapZoomValue = mapa.getZoom();

						console.log("zoom leaflet: " + mapa.getZoom();

						console.log("zoom slider: " + this.value);

					}*/

					markerClusters = L.markerClusterGroup();

					mapa.addLayer(markerClusters);

					mapa.on('click', function(e) {

					    fecharPainelPreencherOcorrencia();
					} );

					// puxa o arquivo geoJson da ufes e adiciona como layer ao mapa
					$.getJSON("ufesBounds.geoJson",function(data){

						ufesGeoJson = L.geoJson(data, {
							style: {	
								fillColor: 'white',
							    weight: 2,
								opacity: 5,
								color: '#081223',  //Outline color
							    fillOpacity: 0.1
							}
						}).addTo(mapa);

						mapa.fitBounds(ufesGeoJson.getBounds());
					});

					// puxa o arquivo geoJson do lago
					$.getJSON("lakeBounds.geoJson", function(data){

						lakeGeoJson = L.geoJson(data);

					});

	   				var categorizacaoPorAssalto = L.easyButton({
					  states: 
					  [{
					    stateName: 'add-markers-assalto',
					    icon: '<img src="img/assalto.png">',
					    title: 'Exibir apenas alertas de assalto',
					    onClick: function(control) {
					    	if (selecionadoAcidente || selecionadoOutro || selecionadoFalso){
					    		return;
					    	}
					    	else {
					    		selecionadoAssalto = true;
					    	}
					      	selecionaPorCategoria(1);
					      	control.state('remove-markers-assalto');
					    }
					  }, 
					  {
					    stateName: 'remove-markers-assalto',
					    icon: '<img src="img/undo.png">',
						title: 'Exibir todos os alertas',
					    onClick: function(control) {
					    	selecionadoAssalto = false;
					      	removeSelecaoPorCategoria(1);
					      	control.state('add-markers-assalto');
					    }
					  }]
					});

					categorizacaoPorAssalto.addTo(mapa);

					var categorizacaoPorAcidente = L.easyButton({
					  states: 
					  [{
					    stateName: 'add-markers-acidente',
					    icon: '<img src="img/acidente.png">',
					    title: 'Exibir apenas alertas de acidente',
					    onClick: function(control) {
					    	if (selecionadoAssalto || selecionadoOutro || selecionadoFalso){
					    		return;
					    	}
					    	else {
					    		selecionadoAcidente = true;
					    	}					    	
					      	selecionaPorCategoria(2);
					      	control.state('remove-markers-acidente');
					    }
					  }, 
					  {
					    stateName: 'remove-markers-acidente',
					    icon: '<img src="img/undo.png">',
						title: 'Exibir todos os alertas',
					    onClick: function(control) {
					    	selecionadoAcidente = false;
					      	removeSelecaoPorCategoria(2);
					      	control.state('add-markers-acidente');
					    }
					  }]
					});

					categorizacaoPorAcidente.addTo(mapa);

					var categorizacaoPorOutros = L.easyButton({
					  states: 
					  [{
					    stateName: 'add-markers-outros',
					    icon: '<img src="img/outro.png">',
					    title: 'Exibir apenas alertas de outras categorias',
					    onClick: function(control) {
					    	if (selecionadoAssalto || selecionadoAcidente || selecionadoFalso){
					    		return;
					    	}
					    	else {
					    		selecionadoOutro = true;
					    	}					    	
					      	selecionaPorCategoria(3);
					      	control.state('remove-markers-outros');
					    }
					  }, 
					  {
					    stateName: 'remove-markers-outros',
					    icon: '<img src="img/undo.png">',
						title: 'Exibir todos os alertas',
					    onClick: function(control) {
					    	selecionadoOutro = false;
					      	removeSelecaoPorCategoria(3);
					      	control.state('add-markers-outros');
					    }
					  }]
					})

					categorizacaoPorOutros.addTo(mapa);

					var categorizacaoPorFalso = L.easyButton({
					  states: 
					  [{
					    stateName: 'add-markers-falso',
					    icon: '<img src="img/falso.png">',
					    title: 'Exibir apenas alertas falsos',
					    onClick: function(control) {
					    	if (selecionadoAssalto || selecionadoAcidente || selecionadoOutro){
					    		return;
					    	}
					    	else {
					    		selecionadoFalso = true;
					    	}					    	
					      	selecionaPorCategoria(4);
					      	control.state('remove-markers-falso');
					    }
					  }, 
					  {
					    stateName: 'remove-markers-falso',
					    icon: '<img src="img/undo.png">',
						title: 'Exibir todos os alertas',
					    onClick: function(control) {
					    	selecionadoFalso = false;
					      	removeSelecaoPorCategoria(4);
					      	control.state('add-markers-falso');
					    }
					  }]
					});

					categorizacaoPorFalso.addTo(mapa);					

		            // Realiza o load dos Markers no mapa
		            updateMaps();

		            // Intervalo de tempo em que os Markers devem ser atualizados
		            setInterval(updateMaps, 1000);

				} // fim inicializarMapa()

				// Atualiza os Markers no mapa
		        function updateMaps() 
		        {

		            var timestamp = new Date().getTime();

		            var tempoPermanenciaHoras = localStorage.getItem("tempo");
		            
		            // Recebe um Json
		            $.get("getMarkers.php", 
		                { 
		                    tempo: tempoPermanenciaHoras, 
		                    t: timestamp

		                }, 
		                function(data) 
		                { 

	                	    if ( data.length == 0 ) {

								console.log("Sem dados no Json recebido de getMarkers.php");
								return;
							}
							else {

								console.log("Json recebido" + data);
							}

		                	data = $.parseJSON(data);

		                	console.log("Tamanho do Json: " + data.length);

		                	console.log("Tamanho do markersArray: " + markersArray.length);

		                	// Para cada alerta obtido via ajax
		                	for (var i = 0; i < data.length; i++){

		                        // Recebe a instância do marcador no mapa, caso já exista
		                        var marcadorNoMapa = getMarcadorNoMapa(data[i].id);
								
								// encontrou um novo marcador
 	                            if (marcadorNoMapa == null){

    	                            // variável para verificar se o marcador está dentro da ufes
 	                            	var isInsideUfes;

 	                            	// variável para verificar se o marcador está dentro do lago
 	                            	var isInsideLake;

									isInsideUfes = leafletPip.pointInLayer([data[i].lng, data[i].lat], ufesGeoJson, true);     	                            	

									isInsideLake = leafletPip.pointInLayer([data[i].lng, data[i].lat], lakeGeoJson, true);    

									// se true, necessita descartar marcador pois está fora do campus
									if (isInsideUfes.length == 0){

										console.log("Fora da ufes: " + data[i].id);

										$.get("delete_alert.php", {id:data[i].id}, function(data){

											if (data != "true"){

												console.log("Não conseguiu deletar o alerta fora do campus");
											}
										});

										continue;
									}
									// se true, necessita descartar marcador pois está dentro do lago
									if (isInsideLake.length != 0){

										console.log("Dentro do lago: " + data[i].id);

										$.get("delete_alert.php", {id:data[i].id}, function(data){

											if (data != "true"){

												console.log("Não conseguiu deletar o alerta dentro do lago");
											}
										});

										continue;
									}

    								console.log("Novo marcador id: " + data[i].id + " status: " + data[i].status);
 	                            }
 	                            else { // marcador é o mesmo mas precisa verificar se tem status diferente

 	                            	if (marcadorNoMapa.options.status != data[i].status){// status é diferente ?

 	                            		console.log("status diferente: " + marcadorNoMapa.options.id);

 	                            		removerMarcadorMarkersArray(marcadorNoMapa.options.id);

			                            markerClusters.removeLayer(marcadorNoMapa);

 	                            		var marcadorIcone = new MarkerIcon({iconUrl:propriedadesStatus[data[i].status].iconUrl});

			                            // Cria uma instância de um marcador no mapa
									    var marker = new customMarker([data[i].lat,data[i].lng],{
									    	icon: marcadorIcone,
									    	id: data[i].id,
									    	lat: data[i].lat,
									    	lng: data[i].lng,
									    	data: data[i].data,
									    	hora: data[i].hora,
									    	nomePessoa: data[i].nomePessoa,
									    	telefonePessoa: data[i].telefone,
									    	status: data[i].status,
									    	loginPessoa: data[i].login
									    }).setBouncingOptions({
										        bounceHeight : 30,    // height of the bouncing
										        bounceSpeed  : 24,    // bouncing speed coefficient
										        exclusive    : false,  // if this marker bouncing all others must stop
										    });

										marker.addTo(mapa).bindPopup(formatarConteudoInfoWindow(data[i].nomePessoa, data[i].data, data[i].hora, data[i].status));

		                                //Define que um clique sobre o marcador abrirá a categorização de alertas

		                                marker.on('click', function() {
		                                    abrirPainelPreencherOcorrencia(this.options.id);
		                                });

		                                mapa.removeLayer(marker);

			                            markerClusters.addLayer(marker);

			                            // Guarda uma referência do marcador no array
			                            markersArray.push(marker);
     	                            } // fim do if de status

 	                            	console.log("Encontrou marcador existente id: " + marcadorNoMapa.options.id);
 	                            	continue;
 	                            }

								var marcadorIcone = new MarkerIcon({iconUrl:propriedadesStatus[data[i].status].iconUrl});

	                            // Cria uma instância de um marcador no mapa
							    var marker = new customMarker([data[i].lat,data[i].lng],{
							    	icon: marcadorIcone,
							    	id: data[i].id,
							    	lat: data[i].lat,
							    	lng: data[i].lng,
							    	data: data[i].data,
							    	hora: data[i].hora,
							    	nomePessoa: data[i].nomePessoa,
							    	telefonePessoa: data[i].telefone,
							    	status: data[i].status,
							    	loginPessoa: data[i].login
							    }).setBouncingOptions({
								        bounceHeight : 30,    // height of the bouncing
								        bounceSpeed  : 24,    // bouncing speed coefficient
								        exclusive    : false,  // if this marker bouncing all others must stop
								    });

								marker.addTo(mapa).bindPopup(formatarConteudoInfoWindow(data[i].nomePessoa, data[i].data, data[i].hora ,data[i].status));

                                //Define que um clique sobre o marcador abrirá a categorização de alertas

                                marker.on('click', function() {
                                    abrirPainelPreencherOcorrencia(this.options.id);
                                });

	                            // Se for um novo marker, toca o alerta e add marker ao cluster
                                if (data[i].status == 0)
                                {
                                    playAlert(marker);

                                    mapa.removeLayer(marker);

	                            	markerClusters.addLayer(marker);

                                } else {

                                	mapa.removeLayer(marker);

                                	markerClusters.addLayer(marker);
                                }

	                            // Guarda uma referência do marcador no array
	                            markersArray.push(marker);
		                	}
		                }
		            );						
		        } // fim updateMaps()

		        //Manda uma requisição ajax para atualizar a ocorrência com a categorização do alerta
		        function updateOcorrencia(id)
		        {
		            // Guarda referência para o painel de preenchimento de ocorrencias
		            var painel = document.getElementById('painel-preencher-ocorrencia');
		            
		            $.post
		            (
		                'update_ocorrencia.php?',
		                {
		                	id : id,
		                    categoria : $('#alerta-categoria').val()
		                },
		                function(data)
		                {
		                    if (data === 'true'){
		                        fecharPainelPreencherOcorrencia();
		                    }
		                }
		            );
		        }

		        // Exibe o painel de preenchimento de ocorrencias
		        function abrirPainelPreencherOcorrencia(id)
		        {
		            // Guarda referência para o painel de preenchimento de ocorrencias
		            var painel = document.getElementById('painel-preencher-ocorrencia');
		                      
		            // Anima o aparecimento da janela
		            $('#painel-preencher-ocorrencia').animate({width: "330px"}, 'fast');
		            
		            // Preenche o painel com a view preencher-ocorrencia
		            $.get('preencher_ocorrencia.php?', {id: id}, function(data){painel.innerHTML = data; console.log("id pedido: " + id + "data preencher: " + data);});
		        }

		        // Fecha o painel de preenchimento de ocorrencias
		        function fecharPainelPreencherOcorrencia()
		        {
		            // Anima o fechamento da janela e elimina todos os seus filhos
		            $('#painel-preencher-ocorrencia').animate({width: "0px"}, 'fast', function(){$('#painel-preencher-ocorrencia').empty();});
		        }

		        // Retorna uma string html com o conteúdo formatado de uma infowindow
		        function formatarConteudoInfoWindow(nomePessoa, data, hora, status)
		        { 
		            return "<p class = 'popupCenter'> <b>" + nomePessoa + "</b><br>" + data + "<br>" + hora + "<br>" +propriedadesStatus[status].label + "<p>";

		        }

		        // Atualiza o status de um "Marker novo" para "Localização aproximada" 
		        function updateStatusMarker(id)
		        {
		            var data = 'update_status.php?id='+id;
		            $.get(data);
		        }

		        // Retorna uma instância do marcador no mapa a partir do id
		        function getMarcadorNoMapa(id)
		        {
		            for (var i = 0; i < markersArray.length; i++)
		            {
		                if (markersArray[i].options.id == id)
		                {
		                	console.log("getMarcadorNoMapa id: " + id);
		                    return markersArray[i];
		                }
		            }
		            return null;
		        }

		        // Remove uma instancia de marcador no mapa a partir do id
		        function removerMarcadorMarkersArray(id){

		        	for (var i = 0; i < markersArray.length; i++)
		            {
		                if (markersArray[i].options.id == id)
		                {
		                	console.log("removeu: " + id);
		                    markersArray.splice(i,1);
		                } 
		            }
		        }

				// Executa o bounce do alerta
		        function playAlert(marker)
		        {
		        	marker.bounce(10);
		        }

		        // Seleciona assalto
		        function selecionaPorCategoria(status){

		        	for (var i = 0; i < markersArray.length; i++){

		        		if (markersArray[i].options.status != status && markersArray[i].options.status != 0){

							markerClusters.removeLayer(markersArray[i]);
		        		}
		        	}
		        }

		        // Exibe todos os alertas
		        function removeSelecaoPorCategoria(status){

		        	for (var i = 0; i < markersArray.length; i++){

		        		if (markersArray[i].options.status != status && markersArray[i].options.status != 0){

							markersArray[i].addTo(mapa).bindPopup(formatarConteudoInfoWindow(markersArray[i].options.nomePessoa, markersArray[i].options.data, markersArray[i].options.hora, markersArray[i].options.status));

	                        //Define que um clique sobre o marcador abrirá a categorização de alertas

	                        markersArray[i].on('click', function() {
	                            abrirPainelPreencherOcorrencia(this.options.id);
	                        });

	                        mapa.removeLayer(markersArray[i]);

	                        markerClusters.addLayer(markersArray[i]);
		        		}
		        	}
		        }

		        function updateRespostaRapida(id)
		        {
		        	//console.log("fieldResposta: " + $('#fieldResposta').val());
		            $.get
		            (
		                "update_resposta_rapida.php?",
		                {
		                    id : id,
		                    resposta : $('#fieldResposta').val()
		                },
		                function(data)
		                {                    
		                    if(data === 'true')
		                    {
		                    	console.log("fieldResposta: OK");
		                    
		                        // Escreve uma mensagem de sucesso
		                        $("#respostaFormGroup").append("<p class='text-success' style='margin-top:5px; background:#dff0d8; padding:4px; border-radius:3px; border: #d6e9c6 solid 1px;'> Resposta enviada com sucesso</p>");
		                        
		                        // Define um intervalo (4 seg) para que a mensagem seja retirada
		                        var intervalo = setInterval
		                        (
		                                function()
		                                {
		                                    // Retira a mensagem
		                                    document.getElementById("respostaFormGroup").removeChild(document.getElementById("respostaFormGroup").lastElementChild);
		                                    
		                                    // Retira o intervalo
		                                    clearInterval(intervalo);
		                                }, 
		                                4000
		                        );
		                    }
		                    else{
		                    	console.log("erro no envio da resposta rapida: " + data);
		                    }
		                }
		            );
		        }

				inicializarMapa();	

			</script>			
		</div>		
	</body>
</html>