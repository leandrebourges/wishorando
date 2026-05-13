<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
    $id = $_GET['id'];
    $sql_rando = "SELECT * FROM sortie WHERE id = $id";
    $result_rando = $conn->query($sql_rando);
    $rando = $result_rando->fetch_assoc();
    $sql_sortie = "SELECT parcours FROM sortie WHERE id = $id";
    $result_sortie = $conn->query($sql_sortie);
    $sortie = $result_sortie->fetch_assoc();
?>

<html>

    <head>
        <title> Page Randonnée </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "detail_rando.css"/>
        <link rel = "stylesheet" href = "https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src = "https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.min.js"></script>
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
                        <?php $gpxFile = "annecy_gpx/" . $sortie['parcours'];?>
                        <script>
                            var map = L.map('map').setView([45.9, 6.1], 13);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 18
                            }).addTo(map);

                            var gpxFile = "<?php echo $gpxFile; ?>";
                            console.log("GPX file path:", gpxFile);

                            // Vérifier si le fichier existe
                            fetch(gpxFile)
                                .then(response => {
                                    if (!response.ok) {
                                        console.error("❌ GPX introuvable :", gpxFile);
                                        return;
                                    }

                                    console.log("✔️ GPX trouvé, chargement…");

                                    new L.GPX(gpxFile, {
                                        async: true,
                                        polyline_options: {
                                            color: "red",
                                            weight: 4
                                        }
                                    })
                                    .on("loaded", function(e) {
                                        console.log("✔️ GPX chargé !");
                                        map.fitBounds(e.target.getBounds());
                                    })
                                    .on("error", function(e) {
                                        console.error("❌ Erreur GPX :", e);
                                    })
                                    .addTo(map);
                                })
                                .catch(err => console.error("❌ Erreur fetch GPX :", err));
                        </script>

                    </div>
                </div>

            </div>

        </main>
        
        
    </body>
</html>