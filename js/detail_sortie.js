// exporter juste la partie sortie (sans dates et types par saison)
async function exportSortie(infos_sortie){

    // lecture du fichier gpx avec fetch
    // le fetch se fait à partir du fichier qui link le script js, du coup vu que dans notre cas c'est
    // detail_rando.php, le gpx_files/ marche
    const response = await fetch("gpx_files/" + infos_sortie.parcours);
    const texte_gpx = await response.text();

    // lire le texte en tant que xml
    const parser = new DOMParser();
    const xml = parser.parseFromString(texte_gpx, "application/xml");

    // récupération des points
    const points = [];
    const points_xml = xml.getElementsByTagName("trkpt");

    // remplir liste des points avec latitude et longitude de chaque point
    for (let point of points_xml){
        points.push([
            parseFloat(point.getAttribute("lat")),
            parseFloat(point.getAttribute("lon"))
        ]);
    }

    const sortie_json = {
        titre: infos_sortie.nom,
        depart_latitude: infos_sortie.depart_latitude,
        depart_longitude: infos_sortie.depart_longitude,
        description: infos_sortie.description,
        distance: infos_sortie.distance,
        denivele: infos_sortie.denivele,
        liste_points: points,
        difficulte: infos_sortie.difficulte,
        etat_chien: infos_sortie.etat_chien
    };

    // transformation en texte JSON car pour l'instant on a un objet JavaScript que on peut pas enregistrer directement
    const jsonString = JSON.stringify(sortie_json, null, 4);

    // création d'un blob, qui est un fichier binaire, que l'utilsateur va download après
    // car navigateur a pas d'endroit ou enregistrer le fichier (sinon il faudrait qu'on le crée et enregistre nous)
    const blob = new Blob([jsonString], {
        type: "application/json"
    });

    // création d'un URL temporaire pour téléchargement
    const url = window.URL.createObjectURL(blob);

    // création lien invisible
    const a = document.createElement("a");
    a.href = url;

    // fais que quand on clique au lieu d'ouvrir l'url, ça télécharge ce que il y a à l'url avec le nom donné
    a.download = infos_sortie.nom + ".json";

    
    document.body.appendChild(a);

    // simule click pour que ça télécharge
    a.click();

    // en enlève le faux lien et l'url du faux fichier
    a.remove();
    window.URL.revokeObjectURL(url);
}