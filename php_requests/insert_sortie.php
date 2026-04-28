<?php
// Paramètres de connexion (include fichier plus tard)
$host = "localhost";
$dbname = "wishorando";     
$username = "root";
$password = "";

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données du formulaire
    $nom = $_GET['nom'];
    $depart_longitude = $_GET['depart_longitude'];
    $depart_latitude = $_GET['depart_latitude'];
    $description = !empty($_GET['description']) ? $_GET['description'] : null;
    $parcours = !empty($_GET['parcours']) ? $_GET['parcours'] : null;
    $distance = $_GET['distance'];
    $denivele = $_GET['denivele'];
    $difficulte = !empty($_GET['difficulte']) ? $_GET['difficulte'] : null;
    $chien_autorise = isset($_GET['chien_autorise']) ? 1 : 0;

    // Requête SQL d'insertion
    $sql = "INSERT INTO sortie (nom, depart_longitude, depart_latitude, description, parcours, distance, denivele, difficulte, chien_autorise)
            VALUES (:nom, :depart_longitude, :depart_latitude, :description, :parcours, :distance, :denivele, :difficulte, :chien_autorise)";

    // Préparation pour empêcher les SQL Injection
    $stmt = $pdo->prepare($sql);

    // Exécution requête
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