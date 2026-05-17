INSERT INTO `sortie` (`nom`, `depart_longitude`, `depart_latitude`, `description`, `parcours`, `distance`, `denivele`, `difficulte`, `etat_chien`) VALUES
('10.24 km 236 m Arbusigny, La Grange, Le Biollay, Chez Boget, Penavex, Arbusigny', 6.21871, 46.0914, 'Haute Savoie, commune d\'Arbusigny 10.24 km 236 m…', '10-24-km-236-m-arbusigny-la-grange-le-biollay-chez-boget-penavex-arbusigny.gpx', 10.24, 320, 'facile', 'autorise'),
('11.25 km 403 m Cusy, Marsinge, Esery, Moussy , Vuret , Arculinge, Cusy', 6.24861, 46.134, 'Haute Savoie commune de Réignier-Esery 11.25 km 403 m…', '11-25-km-403-m-cusy-marsinge-esery-moussy-vuret-arculinge-cusy.gpx', 11.25, 690, 'moyen', 'laisse'),
('Entre rives droite et gauche du Tech', 2.6347, 42.4576, 'Au départ d\'Arles sur Tech, le circuit grimpe…', '66-entre-rives-droite-et-gauche-du-tech-en-vallespir.gpx', 66, 2000, 'difficile', 'interdit'),
('ABC - Sortie Locale 30/01/2011 - 40 km', 3.22028, 50.1752, 'ABC - Sortie Locale 30/01/2011 - 40 km', 'abc-sortie-locale-30-01-2011-40-km.gpx', 40, 430, 'moyen', 'interdit');

INSERT INTO `type` (`nom`) VALUES
('à pied'),
('vélo'),
('ski'),
('raquette');
