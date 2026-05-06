import { getElevation } from "./fonctions_utilitaires";

// L est donné par le fichier js qu'on a récupéré du script de leaflet dans le header de la page
// et permet d'accéder aux fonctions de leaflet

// 'map' est l'id du div dans lequel on va mettre la carte leaflet
var map = L.map('map');
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let points = [];

// polyline prend les points dans l'ordre du tableau et les relie 2 à 2 par des lignes droite
let polyline = L.polyline(points).addTo(map);

function createMapPoint(e){
    // l'event onclick sur la map de leaflet a les longitude et lagitude en plus des donnés habituel de l'event onclick
    let latitude = e.latlng.lat;
    let longitude = e.latlng.lng;
    let altitude = getElevation(latitude, longitude);

    if(altitude === null){
        alert("Problème lors de l'ajout du point, veuillez réessayer");
        return;
    }

    // ajoute point à la liste de points
    points.push([latitude, longitude, altitude]);

    // crée un point sur la map (leaflet ignore altitude donc on le met pas)
    L.marker([latitude, longitude]).addTo(map);

    // met à jour la polyline
    polyline.setLatLngs(points);

    // stocker liste des coordonnées pour l'envoi au php dans l'input caché
    document.getElementById('parcours_points_coords').value = JSON.stringify(points);
}

// Fait en sorte que l'event click sur la map appelle la fonction qui crée le point et met à jour le visuel sur la map
map.addEventListener("click", createMapPoint)