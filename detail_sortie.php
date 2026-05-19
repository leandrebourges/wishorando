<!DOCTYPE html>

<?php
    include_once 'php_requests/connexion_bdd.php';
    $id = $_GET['id'];
    $sql_sortie = "SELECT * FROM sortie WHERE id = ?";
    $preparation = mysqli_prepare($conn, $sql_sortie);
    mysqli_stmt_bind_param(
        $preparation,
        "i", // i pour int
        $id,
    );
    // exécution requête
    mysqli_stmt_execute($preparation);
    $sortie = mysqli_fetch_assoc(mysqli_stmt_get_result($preparation));

    // types de sortie (et saisons liées)
    $sql_types = "SELECT  t.nom, a.saison FROM affectation a 
    JOIN type t ON t.id = a.id_type
    WHERE a.id_sortie = ?";

    $preparation = mysqli_prepare($conn, $sql_types);
    mysqli_stmt_bind_param(
        $preparation, 
        "i", // i pour int
        $id
    );
    mysqli_stmt_execute($preparation);
    $types = mysqli_fetch_all(mysqli_stmt_get_result($preparation), MYSQLI_ASSOC);


    // dates de realisations de la sortie 
    // ordre : date la plus haute à plus basse (donc si on a une en 2026 et autre en 2024 celle de 2026 en premier)
    $sql_dates = "SELECT r.date FROM date_sortie ds
        JOIN realisation r ON r.id = ds.id_realisation
        WHERE ds.id_sortie = ?
        ORDER BY r.date DESC
    ";

    $preparation = mysqli_prepare($conn, $sql_dates);
    mysqli_stmt_bind_param(
        $preparation, 
        "i", // i pour int
        $id
    );
    mysqli_stmt_execute($preparation);
    $dates = mysqli_fetch_all(mysqli_stmt_get_result($preparation), MYSQLI_ASSOC);


    // lecture du gpxp our les points du parcours (et point de départ si pas de parcours)
    $gpx = simplexml_load_file("gpx_files/" . $sortie["parcours"]);
    $points_parcours = [];
    foreach ($gpx->trk as $trk) {
        foreach($trk->trkseg as $seg){
            foreach($seg->trkpt as $point){
                array_push($points_parcours, [$point["lat"], $point["lon"]]);
            }}}
    unset($gpx);
?>

<html>

    <head>
        <title> Page Randonnée </title>
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "css/detail_sortie.css"/>
        <link rel = "stylesheet" href = "https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src = "https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.min.js"></script>
    </head> 

    <body>

        <header>
            <?php include_once 'navbar.php';?>
        </header>

        <main>

            <h2>
                <?php
                    echo "{$sortie['nom']}";
                ?>
            </h2>

            <div class = "container">

                <div class = "informations">
                    <div class = "sortie">
                        <div><strong>Description : </strong><?php echo "{$sortie['description']}"; ?></div>
                        
                        <br>

                        <!-- Distance et dénivelé affiché si il y a un parcours (et donc que distance != 0) -->
                            <?php
                                if($sortie['distance'] != 0){
                                    echo "
                                        <div> <strong>Distance : </strong> {$sortie['distance']} km </div>
                                        <br>
                                        <div><strong>Dénivelé : </strong> {$sortie['denivele']} m</div>
                                        <br>
                                    ";
                                }
                            ?>
                        <div><strong>Difficulté : </strong><?php echo "{$sortie['difficulte']}"; ?></div>
                        
                        <br>

                        <div><strong>Type de sortie (par saison) :</strong>
                            <?php 
                                $saisons = ["été", "hiver", "printemps", "automne"];

                                // une ligne par saison (si elle a des types)
                                foreach($saisons as $saison){
                                    $types_saison = [];
                                    foreach($types as $type){
                                        if($type["saison"] == $saison){
                                            array_push($types_saison, $type["nom"]);
                                        }
                                    }

                                    // écrire saison que si elle a des types
                                    if(count($types_saison) != 0){
                                        echo "<br> - {$saison} : ";
                                        
                                        // écrire chaque type de la saison (l'index est stocké dans $index)
                                        foreach($types_saison as $index => $type){
                                            echo " {$type}";
                                            if($index != count($types_saison)-1){
                                                echo ",";
                                            }
                                        }
                                    }
                                }
                            ?>
                        </div>

                        <br>
                        
                        <div><strong>Dates : </strong>
                            <?php 
                                foreach($dates as $date){
                                    $datetime = new DateTime($date["date"]);
                                    $date_reformatee = $datetime->format("d/m/Y \à H\hi");
                                    echo "<br> {$date_reformatee}";
                                }
                            
                            ?>
                        </div>
                        
                        <br>
                        
                        <div><strong>Réglementation pour chiens : </strong><?php echo "{$sortie['etat_chien']}"; ?></div>
                        
                        <!-- besoin de faire htmlspecialchars avec ENT_QUOTES pour éviter les que les ' dans les strings par ex dans nom de la sortie soient pris comme des fin de string par l'html -->
                        <button class="bouton_export" onclick='exportSortie(<?php echo htmlspecialchars(json_encode($sortie), ENT_QUOTES, "UTF-8"); ?>)'> 
                            Exporter cette sortie
                        </button>
                    </div>
                </div>

                <div class = "affichage">
                    <div class = "carte">
                        <div id = "map">
                            
                        </div>
                    </div>
                </div>

            </div>

        </main>
        

        <!-- affiche parcours sur la carte -->
        <script>
            const infos_markers = [
                <?php
                    foreach($points_parcours as $point){
                        echo "[{$point[0]}, {$point[1]}],";
                    }
                ?>];

            var map = L.map('map').setView(infos_markers[0], 14);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            infos_markers.forEach(infos => {
                var marker = L.marker([infos[0], infos[1]]).addTo(map);
            });

            if(infos_markers.length >= 2){
                // pour avoir markers de couleur différente : https://stackoverflow.com/a/35847937
            
                // marker de départ en vert
                var greenIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                L.marker(infos_markers[0], {icon: greenIcon}).addTo(map)
                .bindPopup("Départ", {autoClose:false})
                .openPopup();

                // marker de fin en rouge
                var redIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                L.marker(infos_markers[infos_markers.length - 1], {icon: redIcon}).addTo(map)
                .bindPopup("Arrivée", {autoClose:false})
                .openPopup();

                let polyline = L.polyline(infos_markers).addTo(map);
            }
            
        </script>

        <script src="js/detail_sortie.js"></script>
    </body>
</html>