let mymap = L.map('mapid').setView([51.505, -0.09], 13);
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicXVpbm91LWt1biIsImEiOiJjamN1aWFpZ2QycWV0MnJsZ283dTc5eTRiIn0.xx76XQLovQFF3TwL4BqLTw', {
  attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
  maxZoom: 18,
  id: 'mapbox.streets',
  accessToken: 'your.mapbox.access.token'
}).addTo(mymap);
let marker = L.marker([51.5, -0.09]).addTo(mymap);
marker.bindPopup("velo");