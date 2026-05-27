-- Base de données : drivelog
-- À importer dans phpMyAdmin

CREATE DATABASE IF NOT EXISTS drivelog CHARACTER SET utf8 COLLATE utf8_general_ci;
USE drivelog;

-- =====================
-- TABLE : utilisateurs
-- =====================
CREATE TABLE utilisateurs (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nom      VARCHAR(100) NOT NULL,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- stocké hashé (password_hash)
    role     ENUM('admin', 'utilisateur') NOT NULL DEFAULT 'utilisateur',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- TABLE : vehicules
-- =====================
CREATE TABLE vehicules (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    immatriculation      VARCHAR(20)  NOT NULL UNIQUE,
    modele               VARCHAR(100) NOT NULL,
    type                 ENUM('citadine', 'utilitaire', 'electrique') NOT NULL,
    date_achat           DATE         NOT NULL,
    km_actuel            INT          NOT NULL DEFAULT 0,
    km_dernier_entretien INT          NOT NULL DEFAULT 0,
    statut               ENUM('Disponible', 'En cours', 'En panne') NOT NULL DEFAULT 'Disponible'
);

-- =====================
-- TABLE : reservations
-- =====================
CREATE TABLE reservations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    id_vehicule  INT  NOT NULL,
    id_user      INT  NOT NULL,
    date_debut   DATE NOT NULL,
    date_fin     DATE NOT NULL,
    km_depart    INT  DEFAULT NULL,
    km_retour    INT  DEFAULT NULL,
    carburant    INT  DEFAULT NULL,          -- niveau en % au retour
    incident     TEXT DEFAULT NULL,          -- description incident éventuel
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (id_vehicule) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user)     REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- =====================
-- DONNÉES DE TEST
-- =====================

-- Un admin et deux employés
INSERT INTO utilisateurs (nom, email, password, role) VALUES
('Admin Logistix',  'admin@logistix.fr',  '$2y$10$abcdefghijklmnopqrstuuVwxyz0123456789ABCDEFGHIJKLMNOP', 'admin'),
('Jean Dupont',     'jean@logistix.fr',   '$2y$10$abcdefghijklmnopqrstuuVwxyz0123456789ABCDEFGHIJKLMNOP', 'utilisateur'),
('Marie Martin',    'marie@logistix.fr',  '$2y$10$abcdefghijklmnopqrstuuVwxyz0123456789ABCDEFGHIJKLMNOP', 'utilisateur');

-- Des véhicules avec des états variés
INSERT INTO vehicules (immatriculation, modele, type, date_achat, km_actuel, km_dernier_entretien, statut) VALUES
('AB-123-CD', 'Renault Clio',      'citadine',    '2021-03-15', 45000, 40000, 'Disponible'),
('EF-456-GH', 'Peugeot Partner',   'utilitaire',  '2020-06-01', 82000, 60000, 'Disponible'),  -- besoin entretien !
('IJ-789-KL', 'Tesla Model 3',     'electrique',  '2022-11-20', 31000, 30000, 'En cours'),
('MN-012-OP', 'Citroën Berlingo',  'utilitaire',  '2019-01-10', 120000, 119000, 'En panne'),
('QR-345-ST', 'Renault Zoé',       'electrique',  '2023-05-05', 15000, 15000, 'Disponible');