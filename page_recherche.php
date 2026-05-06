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
            <?php include_once 'barre_recherche.php';?>
        </header>

        <main>

            <div class = "container">
                <div class = "liste_randos">
                    <h2>Liste des randonnées : </h2>
                    <div class = "sortie">
                        <?php
                            while ($rando = $result_rando->fetch_assoc()){
                                echo "<div class = 'rando'>Nom : {$rando['nom']}<br>
                                        Distance : {$rando['distance']}<br>
                                        <a class = 'detail' href = 'detail.php?id={$rando['id']}'>Plus de détails</a>
                                      </div>";
                            }
                        ?>

                    </div>
                </div>

                <div class = "affichage">
                    <h2>Carte : </h2>
                    <div class = "carte">
                        <img class = "img" src = "images.jpg"/>
                    </div>
                </div> 
            </div>
            

        </main>

        <footer>
            <?php include_once 'footer.php';?>
        </footer>

    </body>


</html>