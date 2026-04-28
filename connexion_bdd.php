<?php
    //connexion à la BD pour l'ensemble de la page 
    mysqli_report(MYSQLI_REPORT_ERROR);
    $conn = @mysqli_connect("localhost", "root", "");

    //if (mysqli_connect_errno()) {
    if (!$conn) { 
    echo "<p class = \"erreur\"> Erreur: " . mysqli_connect_error(). "</p>";
    } else {  
    // Sélection de la base de données
    mysqli_select_db($conn, "wishorando");                                 
    // Encodage UTF8 pour les échanges avecla BD
    mysqli_query($conn, "SET NAMES UTF8");  
    // echo "Base de données connectée !";
    }

    $sql_rando = "SELECT nom FROM sortie";
    $result_rando = mysqli_query($conn, $sql_rando);
    $rando = mysqli_fetch_assoc($result_rando)["nom"];

    $sql_distance = "SELECT distance FROM sortie";
    $result_distance = mysqli_query($conn, $sql_distance);
    $distance = mysqli_fetch_assoc($result_distance)["distance"];
?>