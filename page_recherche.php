<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
    $sql_rando = "SELECT id, nom, distance FROM sortie";
    $result_rando = $conn->query($sql_rando);
?>

<html> 

    <head>
        <title> Recherche de randonnées </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "page_recherche.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
                                        Distance : {$rando['distance']} km<br>
                                        <a class = 'detail' href = 'detail_rando.php?id={$rando['id']}'>Plus de détails</a>
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


        <!-- NE PAS TOUCHER !! Affichage de la carte ! -->
        <script>

            const map = L.map('map').setView([45.905, 6.13], 12);

            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = L.marker([45.905, 6.13]).addTo(map);

            var popup = L.popup()
            .setLatLng([45.915, 6.13])
            .setContent("I am a standalone popup.")
            .openOn(map);

            var popup = L.popup();

            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent("You clicked the map at " + e.latlng.toString())
                    .openOn(map);
            }

            map.on('click', onMapClick);

        </script>

    </body>


</html>