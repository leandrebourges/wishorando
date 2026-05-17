INSERT INTO `sortie` (`id`, `nom`, `depart_longitude`, `depart_latitude`, `description`, `parcours`, `distance`, `denivele`, `difficulte`, `etat_chien`) VALUES
(1, '10.24 km 236 m Arbusigny, La Grange, Le Biollay, Chez Boget, Penavex, Arbusigny', 6.21871, 46.0914, 'Haute Savoie, commune d\'Arbusigny 10.24 km 236 m…', '10-24-km-236-m-arbusigny-la-grange-le-biollay-chez-boget-penavex-arbusigny.gpx', 10.24, 320, 'facile', 'autorise'),
(2, '11.25 km 403 m Cusy, Marsinge, Esery, Moussy , Vuret , Arculinge, Cusy', 6.24861, 46.134, 'Haute Savoie commune de Réignier-Esery 11.25 km 403 m…', '11-25-km-403-m-cusy-marsinge-esery-moussy-vuret-arculinge-cusy.gpx', 11.25, 690, 'moyen', 'laisse'),
(3, 'Entre rives droite et gauche du Tech', 2.6347, 42.4576, 'Au départ d\'Arles sur Tech, le circuit grimpe…', '66-entre-rives-droite-et-gauche-du-tech-en-vallespir.gpx', 66, 2000, 'difficile', 'interdit'),
(4, 'ABC - Sortie Locale 30/01/2011 - 40 km', 3.22028, 50.1752, 'ABC - Sortie Locale 30/01/2011 - 40 km', 'abc-sortie-locale-30-01-2011-40-km.gpx', 40, 430, 'moyen', 'interdit');

INSERT INTO `type` (`id`, `nom`) VALUES
(1, 'à pied'),
(2, 'vélo'),
(3, 'ski'),
(4, 'raquette');

INSERT INTO realisation (`id`, `date`) VALUES
(1, '2026-10-24 09:00:00'),
(2, '2026-11-25 08:00:00'),
(3, '2026-05-25 10:30:00'),
(4, '2011-01-30 12:00:00');

INSERT INTO affectation (id_type, id_sortie, saison) VALUES
(1, 1, 'hiver'),
(1, 2, 'hiver'),
(1, 3, 'hiver'),
(2, 4, 'printemps');

INSERT INTO date_sortie (id_sortie, id_realisation) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);