import { getElevation } from "./fonctions_utilitaires.js"; // pour avoir elevation pour les points trajet


//////////////////////
// Partie carte //////
//////////////////////

// L est donné par le fichier js qu'on a récupéré du script de leaflet dans le header de la page
// et permet d'accéder aux fonctions de leaflet

// 'map' est l'id du div dans lequel on va mettre la carte leaflet
var map = L.map('map').setView([45.905, 6.13], 14);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let points = [];
let markers = [];

// polyline prend les points dans l'ordre du tableau et les relie 2 à 2 par des lignes droite
let polyline = L.polyline(points).addTo(map);

async function createMapPoint(e){
    // l'event onclick sur la map de leaflet a les longitude et lagitude en plus des donnés habituel de l'event onclick
    let latitude = e.latlng.lat;
    let longitude = e.latlng.lng;
    let altitude = await getElevation(latitude, longitude);

    if(altitude === null){
        alert("Problème lors de l'ajout du point, veuillez réessayer");
        return;
    }

    // ajoute point à la liste de points
    points.push([latitude, longitude, altitude]);

    // crée un point sur la map (leaflet ignore altitude donc on le met pas)
    let marker = L.marker([latitude, longitude]);
    marker.addTo(map);
    markers.push(marker);

    // met à jour la polyline
    polyline.setLatLngs(points);

    // stocker liste des coordonnées pour l'envoi au php dans l'input caché
    document.getElementById('parcours_points_coords').value = JSON.stringify(points);
}

// Fait en sorte que l'event click sur la map appelle la fonction qui crée le point et met à jour le visuel sur la map
map.addEventListener("click", createMapPoint)




const checkboxSortieTrajet = document.getElementById("checkbox_sortie_avec_trajet");
const mapDiv = document.getElementById("map");
const inputCacheCoordsParcours = document.getElementById('parcours_points_coords');
const divCoordsSansTrajet = document.getElementById("coords_sortie_sans_trajet");
const latitudeInput = document.getElementById("latitude");
const longitudeInput = document.getElementById("longitude");

// update l'affichage en fonction du style de sortie 
// (soit on a un trajet donc la carte soit on a juste la latitude longitude du point de la sortie)
// on met pas les inputs en hidden car on gère dans insert_sortie pour voir quoi envoyer dans la requête SQL
function updateStyleSortie() {
    if (checkboxSortieTrajet.checked) {
        mapDiv.style.display = "block";
        divCoordsSansTrajet.style.display = "none";

        // désactive inputs pour pas quel les valeurs soient envoyés
        latitudeInput.disabled = true;
        longitudeInput.disabled = true;

        // active input caché
        inputCacheCoordsParcours.disabled = false;

        // Besoin de faire ça car la map leaflet n'aime pas avoir été créé en étant invisible
        // si on fait pas ça elle n'arrive pas à bien réapparaître 
        setTimeout(() => {
            map.invalidateSize();
        }, 100);

    } else {
        mapDiv.style.display = "none";
        divCoordsSansTrajet.style.display = "block";

        // reset les points
        markers.forEach(marker =>{
            map.removeLayer(marker);
        })
        points = [];
        markers = [];

        // met à jour la polyline
        polyline.setLatLngs(points);
        
        // reset input caché
        inputCacheCoordsParcours.value = "";
        inputCacheCoordsParcours.disabled = true;

        // reactive les inputs pour qu'ils soient envoyés
        latitudeInput.disabled = false;
        longitudeInput.disabled = false;
    }
}

checkboxSortieTrajet.addEventListener("change", updateStyleSortie);
updateStyleSortie();


// gestion envoi form
const form = document.querySelector("form");

// faire l'envoi du form avec fetch pour pouvoir réagir si il y a une erreur de création et afficher une erreur
form.addEventListener("submit", async (e) => {
    e.preventDefault(); // empêche l'envoi de base du form

    // recup les infos du form qui seraient envoyées si on avait juste laissé l'envoi de base
    const formData = new FormData(form);

    try {
        const response = await fetch("php_requests/insert_sortie.php", {
            method: "POST",
            body: formData
        });
        console.log(response);

        if (!response.ok) {
            const data = await response.json();
            throw new Error(data.message);
        }

        alert("Sortie créée !");

    } catch (err) {
        alert(err.message);
    }
});