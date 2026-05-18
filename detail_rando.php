<!DOCTYPE html>

<?php
    include_once 'connexion_bdd.php';
    $id = $_GET['id'];
    $sql_rando = "SELECT * FROM sortie WHERE id = $id";
    $result_rando = $conn->query($sql_rando);
    $rando = $result_rando->fetch_assoc();

    $gpx = simplexml_load_file("gpx_files/" . $rando["parcours"]);
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
                        <?php
                            if($rando['distance'] != 0){
                                echo "
                                    <div> <strong>Distance : </strong> {$rando['distance']} km </div>
                                    <br>
                                    <div><strong>Dénivelé : </strong> {$rando['denivele']} m</div>
                                    <br>
                                ";
                            }
                        ?>
                        <div><strong>Difficulté : </strong><?php echo "{$rando['difficulte']}"; ?></div>
                        <br>
                        <div><strong>Réglementation pour chiens : </strong><?php echo "{$rando['etat_chien']}"; ?></div>
                        <br>
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

    </body>
</html>