-- =====================================================
-- AGRIMARKET CAMEROUN - Base de données MySQL
-- =====================================================
-- Créé le: 26 Avril 2026
-- Application: AgriMarket Cameroun (Marketplace agricole)
-- =====================================================

-- =====================================================
-- 1. TABLE DES UTILISATEURS
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('acheteur', 'vendeur', 'transporteur', 'admin') DEFAULT 'acheteur',
    statut ENUM('actif', 'inactif', 'en_attente') DEFAULT 'en_attente',
    photo_profil VARCHAR(255),
    date_verification DATETIME,
    note_moyenne DECIMAL(3,2) DEFAULT 0.00,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 2. TABLE DES CATÉGORIES DE PRODUITS
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    icone VARCHAR(50),
    parent_id INT NULL,
    actif BOOLEAN DEFAULT TRUE,
    ordre_affichage INT DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 3. TABLE DES PRODUITS
-- =====================================================
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    categorie_id INT NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix_unitaire DECIMAL(12,0) NOT NULL,
    unite VARCHAR(20) DEFAULT 'kg',
    quantite_disponible INT DEFAULT 0,
    quantite_minimum INT DEFAULT 1,
    region VARCHAR(100),
    ville VARCHAR(100),
    statut_stock ENUM('en_stock', 'stock_limite', 'rupture') DEFAULT 'en_stock',
    image_principale VARCHAR(255),
    images JSON,
    actif BOOLEAN DEFAULT TRUE,
    note_moyenne DECIMAL(3,2) DEFAULT 0.00,
    nb_ventes INT DEFAULT 0,
    nb_vues INT DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 4. TABLE DES PRODUITS PHYTO-SANITAIRES
-- =====================================================
CREATE TABLE IF NOT EXISTS produits_phyto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    categorie ENUM('engrais', 'semences', 'pesticides', 'equipement') NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix DECIMAL(12,0) NOT NULL,
    unite VARCHAR(20),
    marque VARCHAR(100),
    composition VARCHAR(255),
    mode_emploi TEXT,
    image VARCHAR(255),
    promo_pourcentage INT DEFAULT 0,
    prix_promo DECIMAL(12,0),
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 5. TABLE DES COMMANDES
-- =====================================================
CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    numero_commande VARCHAR(50) UNIQUE NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee') DEFAULT 'en_attente',
    sous_total DECIMAL(12,0) NOT NULL,
    frais_livraison DECIMAL(12,0) DEFAULT 0,
    montant_total DECIMAL(12,0) NOT NULL,
    mode_paiement ENUM('mtn_momo', 'orange_money', 'especes') NOT NULL,
    reference_paiement VARCHAR(100),
    statut_paiement ENUM('en_attente', 'paye', 'echoue') DEFAULT 'en_attente',
    date_paiement DATETIME,
    nom_livraison VARCHAR(100),
    telephone_livraison VARCHAR(20),
    adresse_livraison TEXT,
    ville_livraison VARCHAR(100),
    notes TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 6. TABLE DES DÉTAILS DES COMMANDES
-- =====================================================
CREATE TABLE IF NOT EXISTS commande_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(12,0) NOT NULL,
    sous_total DECIMAL(12,0) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 7. TABLE DU PANIER
-- =====================================================
CREATE TABLE IF NOT EXISTS paniers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT DEFAULT 1,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_panier (user_id, produit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 8. TABLE DES PROJETS DE FINANCEMENT
-- =====================================================
CREATE TABLE IF NOT EXISTS projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    createur_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    type_projet ENUM('elevage', 'culture', 'transformation', 'pisciculture') NOT NULL,
    budget_total DECIMAL(12,0) NOT NULL,
    montant_collecte DECIMAL(12,0) DEFAULT 0,
    taux_retour DECIMAL(5,2) NOT NULL,
    duree_mois INT NOT NULL,
    localisation VARCHAR(100),
    statut ENUM('en_cours', 'finance', 'echoue') DEFAULT 'en_cours',
    date_fin DATETIME,
    image VARCHAR(255),
    business_plan TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (createur_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 9. TABLE DES INVESTISSEMENTS
-- =====================================================
CREATE TABLE IF NOT EXISTS investissements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projet_id INT NOT NULL,
    investisseur_id INT NOT NULL,
    montant DECIMAL(12,0) NOT NULL,
    date_investissement DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'confirme', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE,
    FOREIGN KEY (investisseur_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 10. TABLE DES TRANSPORTEURS
-- =====================================================
CREATE TABLE IF NOT EXISTS transporteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nom_entreprise VARCHAR(200) NOT NULL,
    description TEXT,
    telephone VARCHAR(20),
    zone_service JSON,
    tarif_km DECIMAL(8,0) DEFAULT 50,
    logo VARCHAR(255),
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 11. TABLE DES LIVRAISONS
-- =====================================================
CREATE TABLE IF NOT EXISTS livraisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    transporteur_id INT,
    numero_suivi VARCHAR(50) UNIQUE NOT NULL,
    statut ENUM('en_attente', 'pris_en_charge', 'en_transit', 'livre', 'probleme') DEFAULT 'en_attente',
    ville_depart VARCHAR(100),
    ville_arrivee VARCHAR(100),
    distance_km INT,
    frais DECIMAL(12,0),
    date_enlevement DATETIME,
    date_livraison_prevue DATETIME,
    date_livraison_reelle DATETIME,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (transporteur_id) REFERENCES transporteurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 12. TABLE DES NOTIFICATIONS
-- =====================================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('commande', 'paiement', 'livraison', 'promotion', 'systeme') NOT NULL,
    titre VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    date_lecture DATETIME,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 13. TABLE DES ÉVALUATIONS
-- =====================================================
CREATE TABLE IF NOT EXISTS evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    evaluateur_id INT NOT NULL,
    evalue_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluateur_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (evalue_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 14. TABLE DES PUBLICITÉS
-- =====================================================
CREATE TABLE IF NOT EXISTS publicites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    budget DECIMAL(12,0),
    nb_clics INT DEFAULT 0,
    nb_vues INT DEFAULT 0,
    statut ENUM('active', 'pause', 'terminee') DEFAULT 'active',
    date_debut DATE,
    date_fin DATE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 15. TABLE DES COMMISSIONS ADMIN
-- =====================================================
CREATE TABLE IF NOT EXISTS commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    montant DECIMAL(12,0) NOT NULL,
    taux DECIMAL(5,2) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 16. TABLE DES PARAMÈTRES SYSTÈME
-- =====================================================
CREATE TABLE IF NOT EXISTS parametres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DONNÉES DE TEST (INSERTS)
-- =====================================================

-- Catégories
INSERT INTO categories (nom, description, icone, ordre_affichage) VALUES
('Légumes', 'Tous types de légumes frais', 'fa-leaf', 1),
('Fruits', 'Fruits frais et tropicaux', 'fa-apple-alt', 2),
('Céréales', 'Maïs, riz, blé, sorgho', 'fa-seedling', 3),
('Tubercules', 'Manioc, igname, patate', 'fa-carrot', 4),
('Élevages', 'Volailles, porcs, chèvres', 'fa-paw', 5);

-- Paramètres système
INSERT INTO parametres (cle, valeur, description) VALUES
('commission_plateforme', '5', 'Taux de commission en pourcentage'),
('frais_livraison_defaut', '2500', 'Frais de livraison par défaut en FCAF'),
('telephone_support', '+237 6XX XXX XXX', 'Numéro de support client'),
('email_support', 'support@agrimarket.cm', 'Email de support');

-- =====================================================
-- INDEX POUR OPTIMISATION
-- =====================================================
CREATE INDEX idx_produits_vendor ON produits(vendor_id);
CREATE INDEX idx_produits_categorie ON produits(categorie_id);
CREATE INDEX idx_produits_statut ON produits(statut_stock);
CREATE INDEX idx_commandes_client ON commandes(client_id);
CREATE INDEX idx_commandes_statut ON commandes(statut);
CREATE INDEX idx_notifications_user ON notifications(user_id, lu);
CREATE INDEX idx_livraisons_suivi ON livraisons(numero_suivi);

-- =====================================================
-- VUES UTILES
-- =====================================================
CREATE OR REPLACE VIEW v_produits_accueil AS
SELECT 
    p.id, p.nom, p.prix_unitaire, p.unite, p.quantite_disponible,
    p.region, p.ville, p.image_principale, p.note_moyenne,
    c.nom as categorie, u.nom as vendor_nom
FROM produits p
JOIN categories c ON p.categorie_id = c.id
JOIN users u ON p.vendor_id = u.id
WHERE p.actif = TRUE
ORDER BY p.nb_ventes DESC
LIMIT 20;

CREATE OR REPLACE VIEW v_commandes_en_cours AS
SELECT 
    c.id, c.numero_commande, c.montant_total, c.statut, c.date_creation,
    u.nom as client_nom, u.telephone as client_tel
FROM commandes c
JOIN users u ON c.client_id = u.id
WHERE c.statut IN ('en_attente', 'confirmee', 'en_preparation', 'en_livraison')
ORDER BY c.date_creation DESC;

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================