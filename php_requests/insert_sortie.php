<?php
include_once 'connexion_bdd.php'; // recup connexion dans $conn

function calculDistance($point1, $point2) {
    // rayon Terre (km)
    $r_terre = 6371; 
    $lat1 = $point1[0];
    $lon1 = $point1[1];
    $lat2 = $point2[0];
    $lon2 = $point2[1];

    // deg2rad pour passer de degré à radian
    $diffLatitude = deg2rad($lat2 - $lat1);
    $diffLongitude = deg2rad($lon2 - $lon1);

    $angle =
        sin($diffLatitude/2) * sin($diffLatitude/2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($diffLongitude/2) * sin($diffLongitude/2);

    $angle = 2 * atan2(sqrt($angle), sqrt(1-$angle));

    return $r_terre * $angle;
}

try {

    // récupération des données du formulaire
    $nom = $_POST['nom'];
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    $sortie_avec_trajet = isset($_POST['sortie_avec_trajet']);
    // points de la forme [latitude, longitude, altitude]
    $parcours_points_coords = isset($_POST['parcours_points_coords']) ? json_decode($_POST['parcours_points_coords'], true) : null;

    // facile, moyen ou difficile
    $difficulte = $_POST['difficulte'];
    // interdit, laisse, autorise
    $etat_chien = $_POST['etat_chien'];
    // été, hiver, printemps, automne
    $saison = $_POST['saison'];
    // id du type
    $type_id = $_POST['type'];
    // datetime
    $date = $_POST['date_sortie'];



    $gpx_filename = null;
    // Génération nom du fichier gpx (si on a un parcours)
    if($parcours_points_coords != null){
        $gpx_filename = $nom . ".gpx";
        // départ à deux car rentre dans la boucle première fois que si il y a déjà un fichier avec le nom
        $i = 2;
        // boucle pour avoir un nom unique (par ex si test.gpx existe on fait test_2.gpx et si il existe on a test_3.gpx etc ...)
        while (file_exists(__DIR__ . "/../gpx_parcours/" . $gpx_filename)) {
            $gpx_filename = $gpx_filename . "_" . $i . ".gpx";
            $i++;
        }
    }


    // utilisation données pour autres informations à sauvegarder dans base de donnée

    // gestion parcours en fonction du type de sortie (avec ou sans parcours) :
    $depart_latitude = 0;
    $depart_longitude = 0;
    $distance = 0;
    $denivele = 0;

    // sortie avec parcours
    if ($parcours_points_coords != null && $sortie_avec_trajet) {
        $depart_latitude = $parcours_points_coords[0][0];
        $depart_longitude = $parcours_points_coords[0][1];

        // calcul distance et denivele
        for ($i = 0; $i < count($parcours_points_coords)-1; $i++) {
            $distance += calculDistance($parcours_points_coords[$i], $parcours_points_coords[$i+1]);
            $diff_denivele = $parcours_points_coords[$i+1][2] - $parcours_points_coords[$i][2];
            if($diff_denivele > 0){
                $denivele += $diff_denivele;
            }
        }
    } 
    // sortie sans parcours
    else {
        // si mode parcours mais que il n'y a même pas de point de départ -> erreur
        if($sortie_avec_trajet){
            throw new Exception("Le parcours n'a aucun point, il faut au moins un point de départ");
        }
        $depart_latitude = $_POST['latitude'];
        $depart_longitude = $_POST['longitude'];
    }

    // requête SQL d'insertion
    $sql = "INSERT INTO sortie (nom, depart_longitude, depart_latitude, description, parcours, distance, denivele, difficulte, etat_chien)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // préparation pour empêcher les SQL Injection
    $preparation = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $preparation,
        "sddssddss", // s pour string et d pour decimal/float
        $nom,
        $depart_longitude,
        $depart_latitude,
        $description,
        $gpx_filename,
        $distance,
        $denivele,
        $difficulte,
        $etat_chien
    );

    // exécution requête
    mysqli_stmt_execute($preparation);
    // récupération de l'id généré
    $id_nouvelle_sortie = mysqli_insert_id($conn);



    // création des liens pour la date / saison / type
    
    // realisation
    $sql = "INSERT INTO realisation (date)
            VALUES (?)";
    $preparation = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $preparation,
        "s", // s pour string (date considéré comme string)
        $date,
    );
    // exécution requête
    mysqli_stmt_execute($preparation);
    // récupération de l'id généré
    $id_nouvelle_realisation = mysqli_insert_id($conn);



    // type et saison
    $sql = "INSERT INTO affectation (id_sortie, id_type, saison)
            VALUES (?, ?, ?)";
    $preparation = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $preparation,
        "iis", // s pour string et i pour int
        $id_nouvelle_sortie,
        $type_id,
        $saison
    );
    // exécution requête
    mysqli_stmt_execute($preparation);


    // lien date et sortie
    $sql = "INSERT INTO date_sortie (id_realisation, id_sortie)
            VALUES (?, ?)";
    $preparation = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $preparation,
        "ii", // s pour string et i pour int
        $id_nouvelle_realisation,
        $id_nouvelle_sortie,
    );
    // exécution requête
    mysqli_stmt_execute($preparation);



    // creation du fichier gpx (qui est de l'XML) si il y a un parcours
    if($parcours_points_coords != null){
        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<gpx version="1.1" creator="Wishorando"></gpx>'
        );

        $trk = $xml->addChild("trk");
        $trkseg = $trk->addChild("trkseg");

        foreach ($parcours_points_coords as $point) {
            $trkpt = $trkseg->addChild("trkpt");
            $trkpt->addAttribute("lat", $point[0]);
            $trkpt->addAttribute("lon", $point[1]);
            // $trkpt->addChild("ele", $point[2]);
        }

        // on fait en tant que DOM pour que ça prenne la forme d'un truc html qui peut être lisible car sinon ça met tout sur une ligne
        $dom = new DOMDocument("1.0", "UTF-8");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // création fichier
        $dom->save(__DIR__ . "/../gpx_files/" . $gpx_filename);
    }

} catch (Exception $e) {
    // code d'erreur 400 - bad request car il y a un problème dans données renseignées par utilisateur
    http_response_code(400);

    header('Content-Type: application/json');

    echo json_encode([
        "message" => "Erreur lors de la création de la sortie : " . $e->getMessage()
    ]);
    exit;
}

echo json_encode([
    "message" => "Création réussie "
]);
exit;
?>