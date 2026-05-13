<?php


?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/page_creation_sortie.css">
            <title>Ajouter une sortie</title>

            <!-- Leaflet.js pour afficher la carte sur laquelle on clique pour faire le projet -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        </head>


        <body>

            <h1>Ajout d'une nouvelle sortie</h1>

            <form action="php_requests/insert_sortie.php" method="POST">

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" maxlength="100" required><br><br>

                <label for="description">Description :</label>
                <textarea id="description" name="description"></textarea><br><br>

                <label for="distance">Distance (km) :</label>
                <input type="number" step="any" id="distance" name="distance" required><br><br>

                <label for="denivele">Dénivelé (m) :</label>
                <input type="number" id="denivele" name="denivele" required><br><br>

                <label for="difficulte">Difficulté :</label>
                <select id="difficulte" name="difficulte">
                    <option value="">--Choisir une difficulté--</option>
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="difficile">Difficile</option>
                </select><br><br>

                <label for="chien_autorise">Chien autorisé ? :</label>
                <input type="checkbox" id="chien_autorise" name="chien_autorise" value="1"><br><br>

                <!-- input caché qui va contenir les coords des différents points du parcours -->
                <input type="hidden" id="parcours_points_coords" name="parcours_points_coords">

                <button type="submit">Envoyer</button>

            </form>

            <div id="map">

            </div>

        </body>


    </html>

