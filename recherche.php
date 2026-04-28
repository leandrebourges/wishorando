
<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $conn = @mysqli_connect("localhost", "root", "")
    or die("Impossible de se connecter : " . mysqli_connect_error());

    mysqli_select_db($conn, "wishorando")
    or die("Impossible de sélectionner la base : " . mysqli_connect_error());

    mysqli_query($conn, "SET NAMES UTF8");
    header("Content-Type: application/json");

    $nom = mysqli_real_escape_string($conn, $_GET["nom"]);
    $sql = "SELECT nom FROM Sortie WHERE nom LIKE '%$nom%'";
    $result = mysqli_query($conn, $sql);
    $data = [];

    if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row["nom"];
    }
    echo json_encode($data);
?>