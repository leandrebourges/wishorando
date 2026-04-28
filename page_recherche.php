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
            <?php include_once 'barre_recherche.php';?>
        </header>

        <main>

            <div class = "liste_randos">
                <h2>Liste des randonnées : </h2>
                <div class = "sortie">
                    <?php echo "<div class = 'rando'>Nom : {$rando}</div>"; ?>
                    <?php echo "<div class = 'dist'>Distance : {$distance}</div>"; ?>
                </div>
            </div>

            <!-- 
            <div class = "affichage">
                <h2>Carte : </h2>
                <div class = "carte">

                </div>

                <div class = "detail">

                </div>

            </div>  -->

        </main>

    </body>


</html>