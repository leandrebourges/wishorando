<?php
// paramètres de connexion (include fichier plus tard)
$host = "localhost";
$dbname = "wishorando";     
$username = "root";
$password = "";

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
    // connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // récupération des données du formulaire
    $nom = $_POST['nom'];
    $description = !empty($_POST['description']) ? $_POST['description'] : null;
    // points de la forme [latitude, longitude, altitude]
    $parcours_points_coords = !empty($_POST['parcours_points_coords']) ? $_POST['parcours_points_coords'] : null;
    $difficulte = !empty($_POST['difficulte']) ? $_POST['difficulte'] : null;
    $chien_autorise = isset($_POST['chien_autorise']) ? 1 : 0;

    // utilisation données pour autres informations à sauvegarder dans base de donnée
    $depart_latitude = $parcours[0][0];
    $depart_longitude = $parcours[0][1];

    // calcul distance et denivele
    $distance = 0;
    $denivele = 0;
    for ($i = 0; $i < count($parcours_points_coords)-1; $i++) {
        $distance += calculDistance($parcours[$i], $parcours[$i+1]);
        $diff_denivele = $parcours[$i+1][2] - $parcours[$i][2];
        if($diff_denivele > 0){
            $denivele += $diff_denivele;
        }
    }

    $parcours = "test";

    // requête SQL d'insertion
    $sql = "INSERT INTO sortie (nom, depart_longitude, depart_latitude, description, parcours, distance, denivele, difficulte, chien_autorise)
            VALUES (:nom, :depart_longitude, :depart_latitude, :description, :parcours, :distance, :denivele, :difficulte, :chien_autorise)";

    // préparation pour empêcher les SQL Injection
    $stmt = $pdo->prepare($sql);

    // exécution requête
    $stmt->execute([
        ':nom' => $nom,
        ':depart_longitude' => $depart_longitude,
        ':depart_latitude' => $depart_latitude,
        ':description' => $description,
        ':parcours' => $parcours,
        ':distance' => $distance,
        ':denivele' => $denivele,
        ':difficulte' => $difficulte,
        ':chien_autorise' => $chien_autorise
    ]);

} catch (PDOException $e) {
    echo "Erreur lors de la création de la sortie : " . $e->getMessage();
}

header("Location: ../page_creation_sortie.php");
exit;
?>