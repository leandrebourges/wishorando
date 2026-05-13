<?php


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" href = "page_recherche.css" />
        <link rel="stylesheet" href="css/page_creation_sortie.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <title>Ajouter une sortie</title>
    </head>


    <body>

        <header>
            <?php include_once 'barre_recherche.php';?>
        </header>

        <h1>Ajout d'une nouvelle sortie</h1>

        <form action="php_requests/insert_sortie.php" method="POST">

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" maxlength="100" required>
            
            <br><br>

            <label for="description">Description :</label>
            <textarea id="description" name="description"></textarea>
            
            <br><br>

            <label for="difficulte">Difficulté :</label>
            <select id="difficulte" name="difficulte">
                <option value="facile">Facile</option>
                <option value="moyen">Moyen</option>
                <option value="difficile">Difficile</option>
            </select>
            
            <br><br>

            <label for="etat_chien">Presence de chien ? :</label>
            <select id="etat_chien" name="etat_chien">
                <option value="autorise">Autorisé</option>
                <option value="laisse">En laisse</option>
                <option value="interdit">Interdit</option>
            </select>
            
            <br><br>

            <!-- input caché qui va contenir les coords des différents points du parcours -->
            <input type="hidden" id="parcours_points_coords" name="parcours_points_coords">

            <div id="coords_sortie_sans_trajet">
                <label for="latitude">Latitude :</label>
                <input type="number" step="0.01" id="latitude" name="latitude">

                <br><br>

                <label for="longitude">Longitude :</label>
                <input type="number" step="0.01" id="longitude" name="longitude">
            </div>

            <br><br>

            <label>
                Sortie avec un trajet
            </label>
            <input type="checkbox" id="checkbox_sortie_avec_trajet" name="sortie_avec_trajet">

            <br><br>

            <button type="submit">Envoyer</button>

        </form>

        <div id="map">
            
        </div>

        <!-- Leaflet.js pour afficher la carte sur laquelle on clique pour faire le projet -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        
        <!-- type = module pour pouvoir faire import -->
        <script type="module" src="js/page_creation_sortie.js"></script>
    </body>
</html>

