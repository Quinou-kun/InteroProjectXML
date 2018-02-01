<?php

$opts = array('http' => array('proxy'=> 'tcp://www-cache:3128', 'request_fulluri'=> true));
stream_context_set_default($opts);


function get_ip() {
    // IP si internet partagé
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    // IP derrière un proxy
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Sinon : IP normale
    else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    }
}

function init(){

    if (!$geo = file_get_contents('http://ip-api.com/xml/'.get_ip().'?fields=lat,lon')) {
        echo file_get_contents("404.html");
    } else {
        $geoXML = simplexml_load_string($geo);
        $geoXSL = new DOMDocument();
        $geoXSL->load('geolocation/geo.xsl');
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($geoXSL);
        file_put_contents('geolocation/geo.xml',$proc->transformToXML($geoXML));
        $geoXML = simplexml_load_file('geolocation/geo.xml');

        createMeteo($geoXML->lat,$geoXML->lon);
    }


//    createMeteo('48.692100', '6.187800');
}

function createHeader(){
    //header de la page html
    $HEADER = <<<END
<!DOCTYPE html>
<html>
<head>
<title>InteroProjectXML</title>
</head>
<link rel="stylesheet" href="css/app.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
   integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
   crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>
END;
    echo"$HEADER";
}

function createMeteo($lat, $lon){
    //simplifie les coord en une seul variable
    $coord = $lat . ',' . $lon;

    //recuperation de la meteo dans l'api
    if (!$meteo = file_get_contents('http://www.infoclimat.fr/public-api/gfs/xml?_ll='.$coord.'&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2')) {
        echo file_get_contents("404.html");
    } else {
        //on convertit en XML le retour de la methode
        $meteoXML = simplexml_load_string($meteo);

        //on remplit le meteo.html pour le rendu grâce a une feuille xsl
        $meteoXSL = new DOMDocument();
        $meteoXSL->load('meteo/meteo.xsl');
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($meteoXSL);
        file_put_contents('meteo/meteo.html',$proc->transformToXML($meteoXML));
        createHeader();
        createVelibs();
        createMap($lat,$lon);
        echo(file_get_contents('meteo/meteo.html'));
    }
}

function createVelibs(){
    //recuperationd des velibs dans l'api
    $station = file_get_contents('http://www.velostanlib.fr/service/carto');
    file_put_contents('station/station.xml', $station);
    $stationXML = simplexml_load_string($station);

    //on remplit le station.xml
    $stationXSL = new DOMDocument();
    $stationXSL->load('station/station.xsl');
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($stationXSL);
    file_put_contents('station/station.xml',$proc->transformToXML($stationXML));
}

function createMap($lat,$lon){
    //creation de la map
    echo"<div id='mapid'></div>";

    //recuperation des stations dans le xml existant
    $station = new DOMDocument();
    $station -> load('station/station.xml');

    //recuperation des markers dans le xml existant
    $markers = $station->getElementsByTagName( "marker" );

    //generation du script créant la map et les markers
    $script = <<<END
<SCRIPT>let mymap = L.map('mapid').setView([$lat, $lon], 14);
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicXVpbm91LWt1biIsImEiOiJjamN1aWFpZ2QycWV0MnJsZ283dTc5eTRiIn0.xx76XQLovQFF3TwL4BqLTw', {
  attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
  maxZoom: 17,
  id: 'mapbox.streets',
  accessToken: 'your.mapbox.access.token'
}).addTo(mymap);

var circle = L.circle([$lat, $lon], {
    color: 'red',
    fillColor: 'red',
    fillOpacity: 0.45,
    radius: 150
}).addTo(mymap);

circle.bindPopup("<h2>Vous êtes ici !</h2>").openPopup();
END;
    foreach($markers as $marker) {
        //ajout des données précises pour chaques station

        //récupération de la station dans l'api
        $stationPrecise = new SimpleXMLElement(file_get_contents('http://www.velostanlib.fr/service/stationdetails/nancy/'.$marker->getAttribute('number')));
        //ajout d'un marker sur la map
        $script .= 'var marker = L.marker(['.$marker->getAttribute('lat') . ','. $marker->getAttribute('lng').']).addTo(mymap);';
        //ajout d'une popup sur le marker
        $script .= 'marker.bindPopup("<h3>'.$marker->getAttribute('name').'<br><br>Vélos disponibles : '.$stationPrecise->available.'<br>Places disponibles : '.$stationPrecise->free.'</h3>");';
    }
    $script .= '</SCRIPT>';
    echo($script);
}
//initialisation de l'application
init();
