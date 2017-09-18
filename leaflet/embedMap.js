function inicializarMapa() {

	var osmSatelliteUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';

	var osmMapUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

	var osmAttrib ='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>';

	var osMap = L.tileLayer(osmMapUrl, {minZoom: 15, maxZoom: 20, attribution: osmAttrib});		

	var osmSatellite = L.tileLayer(osmSatelliteUrl, {minZoom: 15, maxZoom: 20, attribution: osmAttrib, subdomains: 'abcd', id: 'mapbox.satellite', accessToken: 'pk.eyJ1IjoiZGFuaWVsZmF2b3JldG8iLCJhIjoiY2o2cjJsc29kMDUwajMycGJ1NW02OGdxMSJ9.EAdQQ-HPcGKe6I0SYLEzOg'});

	var baseLayers = {"osMap": osMap, "Satellite": osmSatellite};

	var mapa = L.map('map', {layers: [osMap], zoomControl: false}).setView([-20.277158, -40.303028], 16);

	var control = L.control.layers(baseLayers, null, {position: 'bottomright'}).addTo(mapa);

	var MarkerIcon = L.Icon.extend({
	    options: {
	        iconSize:     [28, 28],
	        iconAnchor:   [22, 20],
	        popupAnchor:  [-3, -30]
	    }
	});

	var novoAlerta = new MarkerIcon({iconUrl: 'img/novoAlerta.png'}),
    	atendidoAlerta = new MarkerIcon({iconUrl: 'img/atendidoAlerta.png'})

    L.marker([-20.277158, -40.303028], {icon: novoAlerta}).addTo(mapa).bindPopup("Novo alerta");
	L.marker([-20.275844, -40.304562], {icon: atendidoAlerta}).addTo(mapa).bindPopup("Alerta atendido");
}