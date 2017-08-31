function initmap() {

	var osmSatelliteUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';

	var osmMapUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

	var osmAttrib ='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>';

	var osMap = L.tileLayer(osmMapUrl, {minZoom: 15, maxZoom: 20, attribution: osmAttrib});		

	var osmSatellite = L.tileLayer(osmSatelliteUrl, {minZoom: 15, maxZoom: 20, attribution: osmAttrib, subdomains: 'abcd', id: 'mapbox.satellite', accessToken: 'pk.eyJ1IjoiZGFuaWVsZmF2b3JldG8iLCJhIjoiY2o2cjJsc29kMDUwajMycGJ1NW02OGdxMSJ9.EAdQQ-HPcGKe6I0SYLEzOg'});

	var baseLayers = {"osMap": osMap, "Satellite": osmSatellite};

	var map = L.map('map', {layers: [osMap]}).setView([-20.277158, -40.303028], 16);

	var control = L.control.layers(baseLayers).addTo(map);

	var MarkerIcon = L.Icon.extend({
	    options: {
	        iconSize:     [28, 28],
	        iconAnchor:   [22, 20],
	        popupAnchor:  [-3, -30]
	    }
	});

	var novoAlerta = new MarkerIcon({iconUrl: 'img/novoAlerta.png'}),
    	atendidoAlerta = new MarkerIcon({iconUrl: 'img/atendidoAlerta.png'})

    L.marker([-20.277158, -40.303028], {icon: novoAlerta}).addTo(map).bindPopup("Novo alerta");
	L.marker([-20.275844, -40.304562], {icon: atendidoAlerta}).addTo(map).bindPopup("Alerta atendido");
}

function setCookie(layer, cvalue, exdays) {

    var d = new Date();

    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

    var expires = "expires="+d.toUTCString();

    document.cookie = layer + "=" + cvalue + ";" + expires;
}

function getCookie(cookieName) {

    var layer = cookieName + "=";

    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {

        var c = ca[i];

        while (c.charAt(0) == ' ') {

            c = c.substring(1);
        }

        if (c.indexOf(layer) == 0) {

            return c.substring(layer.length, c.length);
        }
    }
    return "";
}

function checkCookie(baseLayers, map) {

    var layerName = getCookie("layer");

    if (layerName == "") {

    	return "";
    }
    else {

    	return layerName;
    }
}

function initializeMap (mapId, layer, lat, lon, zoomDefault, baseLayers){

	var cookieResult = checkCookie();

	var map;

	if (cookieResult == ""){

		map = L.map(mapId, {layers: [layer]}).setView([lat, lon], zoomDefault);

		return map;

	}
	else {

		return;

	}

}