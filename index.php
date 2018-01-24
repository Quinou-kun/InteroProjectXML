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
    $geo = file_get_contents('http://ip-api.com/xml?fields=lat,lon');
    $geoXML = simplexml_load_string($geo);
    $geoXSL = new DOMDocument();
    $geoXSL->load('geolocation/geo.xsl');
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($geoXSL);
    file_put_contents('geolocation/geo.xml',$proc->transformToXML($geoXML));
    $geoXML = simplexml_load_file('geolocation/geo.xml');
    createMeteo($geoXML->lat,$geoXML->lon);
}

function createMeteo($lat, $lon){
    $coord = $lat . ',' . $lon;
    $meteo = file_get_contents('http://www.infoclimat.fr/public-api/gfs/xml?_ll='.$coord.'&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2');
    $meteoXML = simplexml_load_string($meteo);

    $meteoXSL = new DOMDocument();
    $meteoXSL->load('meteo/meteo.xsl');
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($meteoXSL);
    file_put_contents('meteo/meteos.xml',$proc->transformToXML($meteoXML));

}


createGeo();