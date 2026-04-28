<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
?>

<html> 

    <head>
        <title> Recherche de randonnées </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "page_recherche.css" />
    </head> 

    <body>

        <header>
            <h1> Wishorando 🏔️ </h1>
        </header>

        <main>

            <div class = "liste_randos">
                <?php
                    $sql_rando = "SELECT nom FROM sortie";
                    $result_rando = mysqli_query($conn, $sql_rando);
                    $rando = mysqli_fetch_assoc($result_rando)["nom"];
                    echo $rando;
                ?>
            </div>

            <!-- 
            <div class = "affichage">

                <div class = "carte">

                </div>

                <div class = "detail">

                </div>

            </div>  -->

        </main>

    </body>


</html>