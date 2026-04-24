<?php


?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/page_creation_sortie.css">
            <title>Ajouter une sortie</title>
        </head>


        <body>

            <h1>Ajout d'une nouvelle sortie</h1>

            <form action="php_requests/insert_sortie.php" method="GET">

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" maxlength="100" required><br><br>

                <label for="depart_longitude">Longitude du point de départ :</label>
                <input type="number" step="any" id="depart_longitude" name="depart_longitude" required><br><br>

                <label for="depart_latitude">Latitude du point de départ :</label>
                <input type="number" step="any" id="depart_latitude" name="depart_latitude" required><br><br>

                <label for="description">Description :</label>
                <textarea id="description" name="description"></textarea><br><br>

                <label for="parcours">Parcours :</label>
                <input type="text" id="parcours" name="parcours" maxlength="50"><br><br>

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

                <button type="submit">Envoyer</button>

            </form>



        </body>


    </html>

