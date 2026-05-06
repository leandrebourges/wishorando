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

        <main class = "container">

            <div class = "box1">
                <h2>
                    <?php
                    $rando = $result_rando->fetch_assoc();
                        echo "{$rando['nom']}";
                    ?>
                </h2>
                <div class = "sortie">
                    <?php
                        // $rando = $result_rando->fetch_assoc();
                        echo "<div class = 'rando'>
                                    Distance : {$rando['distance']} km<br>
                                </div>";
                    ?>

                </div>

            </div>

            <div class = "affichage">
                <div class = "carte">
                    <div id = "map" style = "width: 100%; height: 100%;"></div>
                </div>
            </div>


        </main>
        
        
    </body>
</html>