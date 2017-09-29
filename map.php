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
								<a href="#"> <img src= "img/conf.png"> </a>
							</li>							
							<li>
								<a href="#"> <img src = "img/report.png"> </a>
							</li>
							<li>
								<a href="#"> <img src="img/exit.png"> </a>
							</li>
						</ul>
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
			    		dataHora: '',
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

					mapa = L.map('map', {layers: [osMap], zoomControl: false}).setView([-20.277158, -40.303028], 16);

					var control = L.control.layers(baseLayers, null, {position: 'bottomright'}).addTo(mapa);

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

					L.easyButton('<img src="img/location.png">', function(btn, mapa){
    					mapa.setView([-20.277158, -40.303028]);
    				}).addTo( mapa );

		            // Realiza o load dos Markers no mapa
		            updateMaps();

		            // Intervalo de tempo em que os Markers devem ser atualizados
		            setInterval(updateMaps, 1000);

				} // fim inicializarMapa()

				// Atualiza os Markers no mapa
		        function updateMaps() 
		        {
		            var timestamp = new Date().getTime();
		            
		            // Recebe um Json
		            $.get("getMarkers.php", 
		                { 
		                    refresh: 1, 
		                    t: timestamp

		                }, 
		                function(data) 
		                { 

	                	    if ( data.length == 0 ) {

								console.log("Sem dados no Json recebido de getMarkers.php");
								return;
							}
							else {

								console.log("Json recebido");
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
									    	dataHora: data[i].dataHora,
									    	nomePessoa: data[i].nome,
									    	telefonePessoa: data[i].telefone,
									    	status: data[i].status,
									    	loginPessoa: data[i].login
									    }).setBouncingOptions({
										        bounceHeight : 30,    // height of the bouncing
										        bounceSpeed  : 24,    // bouncing speed coefficient
										        exclusive    : false,  // if this marker bouncing all others must stop
										    });

										marker.addTo(mapa).bindPopup(formatarConteudoInfoWindow(data[i].nome, data[i].dataHora, data[i].status));

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
							    	dataHora: data[i].dataHora,
							    	nomePessoa: data[i].nome,
							    	telefonePessoa: data[i].telefone,
							    	status: data[i].status,
							    	loginPessoa: data[i].login
							    }).setBouncingOptions({
								        bounceHeight : 30,    // height of the bouncing
								        bounceSpeed  : 24,    // bouncing speed coefficient
								        exclusive    : false,  // if this marker bouncing all others must stop
								    });

								marker.addTo(mapa).bindPopup(formatarConteudoInfoWindow(data[i].nome, data[i].dataHora, data[i].status));

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
		            $('#painel-preencher-ocorrencia').animate({width: "260px"}, 'fast');
		            
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
		        function formatarConteudoInfoWindow(nomePessoa, dataHora, status)
		        { 
		            //return "<b>"+nomePessoa+"</b><br>"+data+" -"+hora+"<br>"+propriedadesStatus[status].label;
		            return "<p class = 'popupCenter'> <b>"+ nomePessoa + "</b><br>" + dataHora + "<br>" + propriedadesStatus[status].label + "<p>";

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

				// Executa o áudio de alerta
		        function playAlert(marker)
		        {

		        	marker.bounce(10);

		        	/*marker.on('click', function() {
    					this.stopBouncing();
					});*/
		            //document.getElementById('audio').play();
		        }

				inicializarMapa();	

			</script>

<!--			<div id="modalConfiguracoesSistema" class="fade modal" role="dialog" tabindex="-1">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<b>Configurações</b>
						</div>		
						<div class="modal-body">
							<form id="formConfiguracao" action="/web/index.php" method="get">
								<input type="hidden" name="r" value="configuracao/update-configuracao">
								<div class='row'>
									<div class='col-md-6'>
										<div class="form-group field-configuracao-tempoesperaparanovoalerta required">
											<label class="control-label" for="configuracao-tempoesperaparanovoalerta">Tempo de Espera para um novo Alerta</label>
											<input type="time" id="configuracao-tempoesperaparanovoalerta" class="form-control" name="tempoEsperaParaNovoAlerta" value="00:01:00" step="1">
											<div class="help-block"></div>
										</div>
										<p> Tempo mínimo que um usuário deverá aguardar se quiser emitir um outro alerta (no caso de ele já haver emitido um).</p>
									</div>
									<div class='col-md-6'>
										<div class="form-group field-configuracao-toleranciaalertasfalsos required">
											<label class="control-label" for="configuracao-toleranciaalertasfalsos">Tolerância de Alertas Falsos</label>
											<input type="number" id="configuracao-toleranciaalertasfalsos" class="form-control" name="toleranciaAlertasFalsos" value="1" min="0">
											<div class="help-block"></div>
										</div>
										<p> Quantidade máxima de Alertas falsos permitida por usuário. Se esta quantidade for ultrapassada, o usuário será bloqueado.</p>
									</div>
								</div>
								<div class='row'> 
									<hr>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<button type="submit" class="btn btn-primary">Salvar</button>
										<a class="btn btn-danger" href="/web/index.php?r=configuracao%2Frestaurar-configuracao" style="margin-left:15px;">Restaurar Configurações Originais</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div id="modalConfiguracoesUsuario" class="fade modal" role="dialog" tabindex="-1">
				<div class="modal-dialog ">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<b>Minhas Preferências</b>
						</div>
						<div class="modal-body">
							<form id="formConfiguracaoUsuario" action="/web/index.php" method="get">
								<input type="hidden" name="r" value="usuario/update-configuracao">
								<div class='row'>
									<div class='col-md-8'>
										<div class="form-group field-usuario-tempopermanenciamarcadores required">
											<label class="control-label" for="usuario-tempopermanenciamarcadores">Tempo de Permanência dos Marcadores no Mapa</label>
											<input type="number" id="usuario-tempopermanenciamarcadores" class="form-control" name="tempoPermanenciaMarcadores" value="1000">

											<div class="help-block"> </div>
										</div>
										<p> A partir do momento em que um alerta for gerado, este será o tempo <code>(em horas)</code> em que o mesmo permanecerá visível no mapa.</p>
									</div>
									<div class='col-md-4'>
										<label>Zoom do Mapa</label>
										<input form ='formConfiguracaoUsuario' id='inputZoomMapa' name='zoomMapa' type='range' min='0' max='20' value='16'>
										<p id='labelZoomMapa' style='text-align:center;'>16</p>
									</div>
								</div>
								<div class='row'>
									<hr>
								</div>
								<div class='row'>
									<div class='col-md-6'>
										<div class="form-group field-usuario-janelaalertas required">

											<input type="hidden" name="janelaAlertas" value="0"><label><input type="checkbox" id="usuario-janelaalertas" name="janelaAlertas" value="1" checked> Exibir janela de alertas</label>

											<div class="help-block"></div>
										</div>
									</div>
									<div class='col-md-6'>
										<div class="form-group field-usuario-exibiralertasatendidos required">

											<input type="hidden" name="exibirAlertasAtendidos" value="0">
											<label>
												<input type="checkbox" id="usuario-exibiralertasatendidos" name="exibirAlertasAtendidos" value="1" checked> Exibir alertas atendidos
											</label>

											<div class="help-block"></div>
										</div>
									</div>
								</div>

								<div class='row'>
									<hr>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<button type="submit" class="btn btn-primary">Salvar </button>
										<a class="btn btn-danger" href="/web/index.php?r=usuario%2Frestaurar-configuracao" style="margin-left:15px;">Restaurar Configurações Originais</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>-->			
		</div>		
	</body>
</html>