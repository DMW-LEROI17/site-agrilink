-- =====================================================
-- AGRIMARKET CAMEROUN - Base de données complète
-- Version: 1.0.0
-- Date: 30 Avril 2026
-- =====================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS agrimarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agrimarket;

-- =====================================================
-- TABLE 1: UTILISATEURS (6 rôles)
-- =====================================================
DROP TABLE IF EXISTS utilisateurs;
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    role ENUM('agriculteur', 'acheteur', 'entreprise_phyto', 'porteur_projet', 'investisseur', 'transporteur', 'admin') NOT NULL DEFAULT 'acheteur',
    statut ENUM('en_attente', 'actif', 'suspendu', 'desactive') NOT NULL DEFAULT 'en_attente',
    photo_profil VARCHAR(255),
    region VARCHAR(100),
    ville VARCHAR(100),
    adresse TEXT,
    note_moyenne DECIMAL(3,2) DEFAULT 0,
    nb_evaluations INT DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_statut (statut)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 2: CATÉGORIES
-- =====================================================
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    icone VARCHAR(50),
    description TEXT,
    parent_id INT NULL,
    ordre_affichage INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 3: PRODUITS AGRICOLES
-- =====================================================
DROP TABLE IF EXISTS produits;
CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendeur_id INT NOT NULL,
    categorie_id INT NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix_unitaire DECIMAL(12,0) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    quantite_disponible INT DEFAULT 0,
    quantite_minimum INT DEFAULT 1,
    region VARCHAR(100),
    ville VARCHAR(100),
    images JSON,
    video_url VARCHAR(255),
    statut_stock ENUM('en_stock', 'stock_limite', 'rupture') DEFAULT 'en_stock',
    note_moyenne DECIMAL(3,2) DEFAULT 0,
    nb_ventes INT DEFAULT 0,
    nb_vues INT DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendeur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    INDEX idx_vendeur (vendeur_id),
    INDEX idx_categorie (categorie_id),
    INDEX idx_region (region)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 4: PRODUITS PHYTO-SANITAIRES
-- =====================================================
DROP TABLE IF EXISTS produits_phyto;
CREATE TABLE produits_phyto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    categorie ENUM('engrais', 'semences', 'pesticides', 'equipement', 'outillage', 'autre') NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    fiche_technique TEXT,
    prix DECIMAL(12,0) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    marque VARCHAR(100),
    promo_pourcentage INT DEFAULT 0,
    promo_date_fin DATE,
    images JSON,
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES utilisateurs(id),
    INDEX idx_entreprise (entreprise_id),
    INDEX idx_categorie (categorie)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 5: PANIERS
-- =====================================================
DROP TABLE IF EXISTS paniers;
CREATE TABLE paniers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(12,0) NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id),
    UNIQUE KEY uk_panier (utilisateur_id, produit_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 6: COMMANDES
-- =====================================================
DROP TABLE IF EXISTS commandes;
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(20) UNIQUE NOT NULL,
    client_id INT NOT NULL,
    statut ENUM('en_attente', 'confirme', 'en_preparation', 'expedie', 'livre', 'annule') DEFAULT 'en_attente',
    sous_total DECIMAL(12,0) DEFAULT 0,
    frais_livraison DECIMAL(12,0) DEFAULT 0,
    montant_total DECIMAL(12,0) NOT NULL,
    mode_paiement ENUM('mtn_momo', 'orange_momo', 'especes', 'virement') NOT NULL,
    statut_paiement ENUM('en_attente', 'paye', 'echoue', 'rembourse') DEFAULT 'en_attente',
    reference_paiement VARCHAR(100),
    date_paiement DATETIME,
    notes TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES utilisateurs(id),
    INDEX idx_client (client_id),
    INDEX idx_statut (statut),
    INDEX idx_numero (numero_commande)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 7: ITEMS DE COMMANDE
-- =====================================================
DROP TABLE IF EXISTS commande_items;
CREATE TABLE commande_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(12,0) NOT NULL,
    sous_total DECIMAL(12,0) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 8: LIVRAISONS
-- =====================================================
DROP TABLE IF EXISTS livraisons;
CREATE TABLE livraisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    transporteur_id INT NOT NULL,
    numero_suivi VARCHAR(50),
    statut ENUM('en_attente', 'collecte', 'en_transit', 'livre', 'retour') DEFAULT 'en_attente',
    adresse_livraison TEXT,
    date_prevue DATETIME,
    date_livraison DATETIME,
    signature_reception VARCHAR(255),
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (transporteur_id) REFERENCES utilisateurs(id),
    INDEX idx_commande (commande_id),
    INDEX idx_suivi (numero_suivi)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 9: PROJETS AGRICOLES
-- =====================================================
DROP TABLE IF EXISTS projets;
CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    porteur_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    type_projet ENUM('elevage', 'culture', 'transformation', 'pisciculture', 'autre') NOT NULL,
    budget_total DECIMAL(12,0) NOT NULL,
    montant_collecte DECIMAL(12,0) DEFAULT 0,
    taux_retour DECIMAL(5,2) DEFAULT 0,
    duree_mois INT DEFAULT 12,
    localisation VARCHAR(100),
    region VARCHAR(100),
    images JSON,
    documents JSON,
    video_presentation VARCHAR(255),
    statut ENUM('brouillon', 'en_validation', 'en_cours', 'termine', 'suspendu') DEFAULT 'brouillon',
    date_lancement DATE,
    date_cloture DATE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (porteur_id) REFERENCES utilisateurs(id),
    INDEX idx_porteur (porteur_id),
    INDEX idx_statut (statut)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 10: INVESTISSEMENTS
-- =====================================================
DROP TABLE IF EXISTS investissements;
CREATE TABLE investissements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projet_id INT NOT NULL,
    investisseur_id INT NOT NULL,
    montant DECIMAL(12,0) NOT NULL,
    mode_paiement VARCHAR(50),
    reference_paiement VARCHAR(100),
    statut ENUM('en_attente', 'confirme', 'echoue') DEFAULT 'en_attente',
    date_investissement DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (projet_id) REFERENCES projets(id),
    FOREIGN KEY (investisseur_id) REFERENCES utilisateurs(id),
    INDEX idx_projet (projet_id),
    INDEX idx_investisseur (investisseur_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 11: TRANSPORTEURS
-- =====================================================
DROP TABLE IF EXISTS transporteurs;
CREATE TABLE transporteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    nom_entreprise VARCHAR(200) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    zone_service JSON,
    tarif_km DECIMAL(6,0) DEFAULT 0,
    capacite_max_kg INT DEFAULT 1000,
    vehicules JSON,
    note_moyenne DECIMAL(3,2) DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 12: NOTIFICATIONS
-- =====================================================
DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    type ENUM('commande', 'paiement', 'livraison', 'projet', 'investissement', 'systeme') NOT NULL,
    titre VARCHAR(200) NOT NULL,
    message TEXT,
    lu BOOLEAN DEFAULT FALSE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_lu (lu)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 13: ÉVALUATIONS
-- =====================================================
DROP TABLE IF EXISTS evaluations;
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluateur_id INT NOT NULL,
    evalue_id INT NOT NULL,
    commande_id INT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evaluateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (evalue_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 14: PUBLICITÉS
-- =====================================================
DROP TABLE IF EXISTS publicites;
CREATE TABLE publicites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    lien VARCHAR(255),
    position ENUM('header', 'sidebar', 'banner', 'popup') DEFAULT 'banner',
    date_debut DATE,
    date_fin DATE,
    nb_clics INT DEFAULT 0,
    nb_impressions INT DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (entreprise_id) REFERENCES utilisateurs(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 15: COMMISSIONS
-- =====================================================
DROP TABLE IF EXISTS commissions;
CREATE TABLE commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    montant DECIMAL(12,0) NOT NULL,
    taux DECIMAL(5,2) DEFAULT 5.00,
    statut ENUM('en_attente', 'paye', 'verse') DEFAULT 'en_attente',
    date_paiement DATETIME,
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 16: PARAMÈTRES
-- =====================================================
DROP TABLE IF EXISTS parametres;
CREATE TABLE parametres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT,
    description VARCHAR(255),
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABLE 17: SESSIONS
-- =====================================================
DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    expire_at DATETIME NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    INDEX idx_token (token),
    INDEX idx_utilisateur (utilisateur_id)
) ENGINE=InnoDB;

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- Catégories
INSERT INTO categories (nom, icone, description, ordre_affichage) VALUES
('Légumes', 'fa-leaf', 'Légumes frais du Cameroun', 1),
('Fruits', 'fa-apple-alt', 'Fruits tropicaux', 2),
('Céréales', 'fa-seedling', 'Maïs, riz, sorgho', 3),
('Tubercules', 'fa-carrot', 'Manioc, igname, patate', 4),
('Élevages', 'fa-paw', 'Volailles, porcs, chèvres', 5),
('Poissons', 'fa-fish', 'Poissons d''élevage', 6);

-- Utilisateurs de test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, statut, region, ville, note_moyenne) VALUES
('Kouam', 'Jean', 'jean@email.com', '$2y$10$abcdefghijklmnopqrstuv', '+237612345678', 'agriculteur', 'actif', 'Ouest', 'Bafoussam', 4.5),
('Tagne', 'Marie', 'marie@email.com', '$2y$10$abcdefghijklmnopqrstuv', '+237612345679', 'acheteur', 'actif', 'Centre', 'Yaoundé', 4.8),
('AgriChem', 'SA', 'contact@agrichem.cm', '$2y$10$abcdefghijklmnopqrstuv', '+237612345680', 'entreprise_phyto', 'actif', 'Littoral', 'Douala', 4.7),
('Nguetch', 'Pierre', 'pierre@email.com', '$2y$10$abcdefghijklmnopqrstuv', '+237612345681', 'porteur_projet', 'actif', 'Centre', 'Yaoundé', 4.2),
('InvestCorp', 'SARL', 'invest@investcorp.cm', '$2y$10$abcdefghijklmnopqrstuv', '+237612345682', 'investisseur', 'actif', 'Littoral', 'Douala', 4.6),
('Kamga', 'Express', 'contact@kamgaexpress.cm', '$2y$10$abcdefghijklmnopqrstuv', '+237612345690', 'transporteur', 'actif', 'Littoral', 'Douala', 4.3);

-- Produits agricoles
INSERT INTO produits (vendeur_id, categorie_id, nom, description, prix_unitaire, unite, quantite_disponible, region, ville, note_moyenne, nb_ventes) VALUES
(1, 3, 'Maïs blanc', 'Maïs blanc de qualité supérieure', 3500, 'kg', 200, 'Ouest', 'Bafoussam', 4.5, 45),
(1, 1, 'Tomates fraîches', 'Tomates fraîches de Foumbot', 1200, 'bassin', 50, 'Ouest', 'Foumbot', 4.7, 32),
(1, 3, 'Arachides', 'Arachides de Garoua', 2800, 'kg', 150, 'Nord', 'Garoua', 4.2, 28),
(1, 4, 'Manioc frais', 'Manioc frais de Yaoundé', 800, 'kg', 300, 'Centre', 'Yaoundé', 4.6, 67),
(1, 2, 'Bananes plantains', 'Bananes plantains de Douala', 500, 'kg', 100, 'Littoral', 'Douala', 4.4, 23),
(1, 1, 'Feuille de manioc', 'Feuilles de manioc fraîches', 2500, 'fagot', 50, 'Ouest', 'Bafoussam', 4.3, 15),
(1, 5, 'Poulets de chair', 'Poulets élevés localement', 4500, 'poulet', 100, 'Centre', 'Yaoundé', 4.8, 56),
(1, 6, 'Tilapia frais', 'Tilapia d''élevage', 2000, 'kg', 80, 'Sud', 'Kribi', 4.5, 34);

-- Produits phyto-sanitaires
INSERT INTO produits_phyto (entreprise_id, categorie, nom, description, prix, unite, marque, promo_pourcentage) VALUES
(3, 'engrais', 'Engrais NPK 20-10-10', 'Engrais NPK pour toutes cultures', 18500, 'sac 50kg', 'AgriChem SA', 0),
(3, 'semences', 'Semences maize hybride', 'Semences à haut rendement (8t/ha)', 4200, 'kg', 'Semences Tropicales', 10),
(3, 'pesticides', 'Insecticide total Bio', 'Contre pucerons, chenilles, aleurodes', 8500, 'L', 'PhytoCam', 0),
(3, 'engrais', 'BioFert Organique', 'Compost biologique enrichi', 12000, 'sac 25kg', 'BioFert', 5),
(3, 'equipement', 'Kit irrigation goutte-à-goutte', 'Pour 500m² - Complete kit', 145000, 'kit', 'IrrigTech', 0),
(3, 'outillage', 'Déchaumeuse manuelle', 'Pour travail du sol', 35000, 'unité', 'AgriTool', 0);

-- Projets agricoles
INSERT INTO projets (porteur_id, titre, description, type_projet, budget_total, montant_collecte, taux_retour, duree_mois, localisation, region, statut) VALUES
(4, 'Élevage poulets de chair', 'Élevage de 500 poulets de chair avec moderne bâtiment', 'elevage', 2500000, 1625000, 22.00, 12, 'Yaoundé', 'Centre', 'en_cours'),
(4, 'Transformation manioc en farine', 'Usine de transformation de manioc en farine de qualité supérieure', 'transformation', 5000000, 2000000, 18.00, 18, 'Bafoussam', 'Ouest', 'en_cours'),
(4, 'Pisciculture tilapia', 'Élevage de tilapia dans étangs modernes', 'pisciculture', 1800000, 1530000, 28.00, 8, 'Kribi', 'Sud', 'en_cours'),
(4, 'Culture maraîchère bio', 'Production de légumes biologiques sous serres', 'culture', 1200000, 450000, 15.00, 6, 'Douala', 'Littoral', 'en_cours');

-- Transporteurs
INSERT INTO transporteurs (utilisateur_id, nom_entreprise, telephone, zone_service, tarif_km, note_moyenne) VALUES
(6, 'Kamga Express', '+237612345690', '["Douala","Yaoundé","Bafoussam"]', 45, 4.3),
(6, 'TransCam Logistique', '+237612345691', '["Douala","Yaoundé","Kribi"]', 50, 4.5),
(6, 'RapidCargo', '+237612345692', '["Toutes régions"]', 55, 4.1);

-- Paramètres
INSERT INTO parametres (cle, valeur, description) VALUES
('commission_taux', '5', 'Taux de commission en pourcentage'),
('frais_livraison_base', '2500', 'Frais de livraison de base'),
('mobile_money_mtn', '+237612345678', 'Numéro MTN MoMo'),
('mobile_money_orange', '+237699999999', 'Numéro Orange Money'),
('email_support', 'support@agrimarket.cm', 'Email de support');

-- =====================================================
-- PROCÉDURES STOCKÉES
-- =====================================================

DELIMITER //

DROP PROCEDURE IF EXISTS sp_creer_commande//
CREATE PROCEDURE sp_creer_commande(
    IN p_client_id INT,
    IN p_mode_paiement VARCHAR(50),
    OUT p_commande_id INT
)
BEGIN
    DECLARE v_numero VARCHAR(20);
    SET v_numero = CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), FLOOR(RAND() * 100));
    
    INSERT INTO commandes (numero_commande, client_id, mode_paiement, montant_total)
    VALUES (v_numero, p_client_id, p_mode_paiement, 0);
    
    SET p_commande_id = LAST_INSERT_ID();
END//

DROP PROCEDURE IF EXISTS sp_calculer_frais_livraison//
CREATE PROCEDURE sp_calculer_frais_livraison(
    IN p_poids DECIMAL(10,2),
    IN p_distance INT,
    OUT p_frais DECIMAL(12,0)
)
BEGIN
    SET p_frais = GREATEST(2500, CAST(p_poids * p_distance * 0.05 AS UNSIGNED));
END//

DELIMITER ;

-- =====================================================
-- VUES
-- =====================================================

DROP VIEW IF EXISTS v_stats_globales//
CREATE VIEW v_stats_globales AS
SELECT 
    (SELECT COUNT(*) FROM utilisateurs WHERE statut = 'actif') AS utilisateurs_actifs,
    (SELECT COUNT(*) FROM produits WHERE actif = TRUE) AS produits_actifs,
    (SELECT COUNT(*) FROM commandes WHERE MONTH(date_creation) = MONTH(NOW())) AS commandes_mois,
    (SELECT COUNT(*) FROM projets WHERE statut = 'en_cours') AS projets_en_cours,
    (SELECT SUM(montant_collecte) FROM projets) AS montant_total_projets;

-- Fin du script