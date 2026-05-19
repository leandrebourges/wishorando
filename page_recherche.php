<!DOCTYPE html>

<?php
    include_once 'php_requests/connexion_bdd.php';
    $sql_rando = "SELECT id, nom, distance, depart_latitude, depart_longitude FROM sortie";
    $result_rando = $conn->query($sql_rando);
?>

<html> 

    <head>
        <title> Recherche de randonnées </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "css/page_recherche.css" />
		<link rel = "stylesheet" href = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity = "sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin = ""/>
		<script src = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity = "sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin = ""></script>

    </head> 


    <body>

        <header>
            <?php include_once 'navbar.php';?>
        </header>

        <main>

            <div class = "container">
                <div class = "liste_randos">
                    <h2>Liste des randonnées : </h2>
                    <div class = "sortie">
                        <?php
                            while ($rando = $result_rando->fetch_assoc()){
                                echo 
                                "<div class = 'rando'>Nom : {$rando['nom']}<br>";
                                    if($rando['distance'] != 0){
                                        echo "Distance : {$rando['distance']} km<br>";
                                    }
                                    else{
                                        echo "Aucun déplacement<br>";
                                    }
                                    
                                    echo "<a class = 'detail' href = 'detail_sortie.php?id={$rando['id']}'>Plus de détails</a>
                                </div>";
                            }
                        ?>

                    </div>
                </div>

                <div class = "affichage">
                    <h2>Carte : </h2>
                    <div class = "carte">
                        <div id = "map" style = "width: 100%; height: 100%;"></div>
                    </div>
                </div> 
            </div>

        </main>

        <footer>
            <?php include_once 'footer.php';?>
        </footer>


        <!-- NE PAS TOUCHER !! Affichage de la carte ! (sauf Léandre) -->
        <script>
            const infos_markers = [
                <?php
                    foreach($result_rando as $rando){
                        echo "[{$rando['depart_latitude']}, {$rando['depart_longitude']},'{$rando['nom']}'],";
                    }
                ?>];

            var map = L.map('map').setView([45.905, 6.13], 14);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            infos_markers.forEach(infos => {
                var marker = L.marker([infos[0], infos[1]]).addTo(map);
                // autoclose pour qu'il reste ouvert 
                // et autoPan pour éviter que la carte aille là ou est dernier point (et reste donc sur Annecy)
                marker.bindPopup(infos[2], {autoClose:false, autoPan: false});
                marker.openPopup();
            });
        </script>

    </body>


</html>