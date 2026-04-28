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

    $sql_rando = "SELECT nom, distance FROM sortie";
    $result_rando = $conn->query($sql_rando);
?>