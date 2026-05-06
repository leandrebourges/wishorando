<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
    $id = $_GET['id'];
    $sql_rando = "SELECT * FROM sortie WHERE id = $id";
    $result_rando = $conn->query($sql_rando);
?>

<html>

    <head>
        <title> Page Randonnée </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "detail_rando.css"/>
    </head> 

    <body>

        <header>
            <?php include_once 'barre_recherche.php';?>
        </header>

        <main>

            <div class = "box1">
                <h2>Affichage de la randonnée sélectionnée</h2>
                <div class = "sortie">
                    <?php
                        $rando = $result_rando->fetch_assoc();
                        echo "<div class = 'rando'>Nom : {$rando['nom']}<br>
                                        Distance : {$rando['distance']}<br>
                                      </div>";
                    ?>

                </div>
            </div>


        </main>
        
        
    </body>
</html>