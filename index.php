<?php


// METEO
// http://www.infoclimat.fr/public-api/gfs/xml?_ll=48.67103,6.15083&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2

//function getUserIpAddr(){
//    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
//        //ip from share internet
//        $ip = $_SERVER['HTTP_CLIENT_IP'];
//    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
//        //ip pass from proxy
//        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//    }else{
//        $ip = $_SERVER['REMOTE_ADDR'];
//    }
//    return $ip;
//}

function createGeo(){
//    $geo = file_get_contents('http://ip-api.com/xml?fields=lat,lon');
//    $geoXML = simplexml_load_string($geo);
//    $geoXSL = new DOMDocument();
//    $geoXSL->load('geolocation/geo.xsl');
//    $proc = new XSLTProcessor();
//    $proc->importStyleSheet($geoXSL);
//    file_put_contents('geolocation/geo.xml',$proc->transformToXML($geoXML));
//    $geoXML = simplexml_load_file('geolocation/geo.xml');


//    createMeteo($geoXML->lat,$geoXML->lon);
    createMeteo('48.692100', '6.187800');
}

function createMeteo($lat, $lon){
    $coord = $lat . ',' . $lon;
    $meteo = file_get_contents('http://www.infoclimat.fr/public-api/gfs/xml?_ll='.$coord.'&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2');
    file_put_contents('meteo/meteos.xml',$meteo);
    $meteoXML = simplexml_load_string($meteo);

    $meteoXSL = new DOMDocument();
    $meteoXSL->load('meteo/meteo.xsl');
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($meteoXSL);
    file_put_contents('meteo/meteo.html',$proc->transformToXML($meteoXML));


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
    createMapHtml();
    setMap($lat,$lon);

}


function createMapHtml(){
    echo"<div id='mapid'></div>";
}

function setMap($lat,$lon){
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

circle.bindPopup("Vous êtes ici !").openPopup();
</SCRIPT>
END;
    echo($script);
}

createGeo();

echo(file_get_contents('meteo/meteo.html'));






function createVelibs(){
    $station = file_get_contents('http://www.velostanlib.fr/service/carto');
    file_put_contents('velibs/station.xml',$station);
}

createVelibs();





