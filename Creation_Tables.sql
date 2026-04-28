-- Suppression des tables si elles existent (ordre important à cause des FK)
DROP TABLE IF EXISTS affectation;
DROP TABLE IF EXISTS date_sortie;
DROP TABLE IF EXISTS Type;
DROP TABLE IF EXISTS Sortie;
DROP TABLE IF EXISTS Realisation;

-- Table Realisation
CREATE TABLE Realisation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL
);

-- Table Sortie
CREATE TABLE Sortie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    depart_longitude FLOAT NOT NULL,
    depart_latitude FLOAT NOT NULL,
    description TEXT,
    parcours VARCHAR(50),   -- Nom du fichier de parcours
    distance FLOAT NOT NULL,
    denivele INT NOT NULL,
    difficulte VARCHAR(50),
    chien_autorise BOOLEAN
);

-- Table Type
CREATE TABLE Type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- Relation date_sortie
CREATE TABLE date_sortie (
    id_realisation INT,
    id_sortie INT,
    PRIMARY KEY (id_realisation, id_sortie),

    CONSTRAINT FK_DATE_SORTIE_REALISATION
        FOREIGN KEY (id_realisation) REFERENCES Realisation(id)
        ON DELETE CASCADE,

    CONSTRAINT FK_DATE_SORTIE_SORTIE
        FOREIGN KEY (id_sortie) REFERENCES Sortie(id)
        ON DELETE CASCADE
);

-- Relation affectation
CREATE TABLE affectation (
    id_sortie INT,
    id_type INT,
    saison VARCHAR(50),

    PRIMARY KEY (id_sortie, id_type, saison),

    CONSTRAINT FK_AFFECTATION_SORTIE
        FOREIGN KEY (id_sortie) REFERENCES Sortie(id)
        ON DELETE CASCADE,

    CONSTRAINT FK_AFFECTATION_TYPE
        FOREIGN KEY (id_type) REFERENCES Type(id)
        ON DELETE CASCADE
);