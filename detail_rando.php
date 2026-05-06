<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
    $id = $_GET['id'];
    $sql_rando = "SELECT * FROM sortie WHERE id = $id";
    $result_rando = $conn->query($sql_rando);
    $rando = $result_rando->fetch_assoc();
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

            <h2>
                <?php
                    echo "{$rando['nom']}";
                ?>
            </h2>

            <div class = "container">

                <div class = "informations">
                    <div class = "sortie">
                        <div><strong>Description : </strong><?php echo "{$rando['description']}"; ?></div>
                        <br>
                        <div><strong>Distance : </strong><?php echo "{$rando['distance']}"; ?> km</div>
                        <br>
                        <div><strong>Dénivelé : </strong><?php echo "{$rando['denivele']}"; ?> m</div>
                        <br>
                        <div><strong>Difficulté : </strong><?php echo "{$rando['difficulte']}"; ?></div>
                        <br>
                        <div><strong>Chien autorisé : </strong><?php echo "{$rando['chien_autorise']}"; ?></div>
                        <br>
                    </div>
                </div>

                <div class = "affichage">
                    <div class = "carte">
                        <div id = "map" style = "width: 100%; height: 100%;"></div>
                    </div>
                </div>

            </div>

        </main>
        
        
    </body>
</html>