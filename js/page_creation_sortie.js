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

// fonction pour ajouter un point à la liste des points du parcours, retourne true si il a bien été rajouté
// et false sinon (par ex si problème lors d'appel pour avoir l'altitude)
async function ajouterPointParcours(latitude, longitude) {
    let altitude = await getElevation(latitude, longitude);

    if(altitude === null){
        return false;
    }

    points.push([latitude, longitude, altitude]);

    let marker = L.marker([latitude, longitude]);
    marker.addTo(map);
    markers.push(marker);

    polyline.setLatLngs(points);

    document.getElementById('parcours_points_coords').value = JSON.stringify(points);

    return true;
}

function resetPointsMap(){
        markers.forEach(marker =>{
            map.removeLayer(marker);
        })
        points = [];
        markers = [];

        // met à jour la polyline
        polyline.setLatLngs(points);
}

// crée un point là ou on clique sur la carte leaflet
async function createPointOnMapClick(e){
    // l'event onclick sur la map de leaflet a les longitude et lagitude en plus des donnés habituel de l'event onclick
    let latitude = e.latlng.lat;
    let longitude = e.latlng.lng;
    
    // ajoute le point (retourne true si ajout réussi, false sinon)
    let ajoutPointReussi = await ajouterPointParcours(latitude, longitude);

    if(!ajoutPointReussi){
        alert("Problème lors de l'ajout du point, veuillez réessayer");
    }
}

// Fait en sorte que l'event click sur la map appelle la fonction qui crée le point et met à jour le visuel sur la map
map.addEventListener("click", createPointOnMapClick)




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
        resetPointsMap();
        
        // reset input caché
        inputCacheCoordsParcours.value = "";
        inputCacheCoordsParcours.disabled = true;

        // reactive les inputs pour qu'ils soient envoyés
        latitudeInput.disabled = false;
        longitudeInput.disabled = false;
    }
}

checkboxSortieTrajet.addEventListener("click", updateStyleSortie);
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



// import d'un json de sortie, qui va remplir les champs de la sortie (sauf date, saison et type) 
// et mettre la map et son parcours si il y en a un


// selection bouton et input file caché
const boutonImport = document.getElementById("bouton_import");
const inputFile = document.getElementById("import_json");

// renvoi du click sur le bouton sur l'input type file qui est caché
boutonImport.addEventListener("click", () => {
    inputFile.click(); // ouvre le sélecteur de fichier
});


// quand utilisateur a fini de sélectionner, ça lance l'evenement change de l'input, 
// et on peut donc utiliser le fichier
inputFile.addEventListener("change", (event) => {
    // objet File de javascript
    const file = event.target.files[0];

    // si pas de fichier sélectionné on ne fait rien
    if (!file){
        return;
    }

    // objet qui permet de lire le fichier
    const reader = new FileReader();

    // on est obligé delui donner une fonction quand il a fini de load car readAsText est asynchrone
    // et on ne peut pas récupérer directement le texte que ça sors
    // quand ça fini de lire ça lance onload avec le text dans l'evenement
    reader.onload = async (e) => {
        try {
            // transformer le contenu reçu en string en objet javascript pour pouvoir lire les infos
            const data = JSON.parse(e.target.result);

            // exemple : remplir les champs
            document.getElementById("nom").value = data.titre;
            document.getElementById("description").value = data.description;
            document.getElementById("difficulte").value = data.difficulte;
            document.getElementById("etat_chien").value = data.etat_chien;

            
            // gestion parcours map

            // reset map
            markers.forEach(m => {
                map.removeLayer(m);
            });
            markers = [];
            points = [];
            polyline.setLatLngs(points);

            

            // ajout des points sur la map si il y en a plus que un (point de départ)
            if (data.liste_points.length > 1) {
                // désactive le checkbox pour changer de mode pendant que ça load
                checkboxSortieTrajet.disabled = true;

                // update checkbox + affichage carte
                checkboxSortieTrajet.checked = true;
                updateStyleSortie();
                
                

                // on peut pas faire de await dans un foreach donc boucle normale
                for (const point of data.liste_points) {
                    const latitude = point[0];
                    const longitude = point[1];

                    let ajoutPointReussi = await ajouterPointParcours(latitude, longitude);
                    // recentrage carte sur là ou il y a les points
                    map.fitBounds(polyline.getBounds());

                    if (!ajoutPointReussi) {
                        resetPointsMap();
                        alert("Problème lors de la récupération des altitudes");
                        return;
                    }
                }

                // réactive le checkbox pour changer de mode une fois que on a fini de load les points
                checkboxSortieTrajet.disabled = false;
            }
            
            // si pas plus d'un point, mettre que le point de départ et rester en mode sans parcours
            else{
                // coords départ
                document.getElementById("latitude").value = data.depart_latitude;
                document.getElementById("longitude").value = data.depart_longitude;
            }
            

        } catch (err) {
            alert("Fichier JSON invalide");
        }
    };

    // lance la lecture du fichier, une fois fini appelle onload avec le contenu sous forme de string
    reader.readAsText(file);
});