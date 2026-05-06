<?php
/**
 * AGRIMARKET CAMEROUN - Script d'installation
 * Version: 1.0.0
 * Date: 30 Avril 2026
 */

// Configuration WAMPServer
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';  // Par défaut WAMPServer n'a pas de mot de passe

echo "============================================\n";
echo "  AGRIMARKET CAMEROUN - Installation\n";
echo "============================================\n\n";

// Connexion sans base de données
try {
    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "[OK] Connexion MySQL réussie\n\n";
} catch (PDOException $e) {
    die("[ERREUR] Connexion MySQL échouée: " . $e->getMessage() . "\n");
}

// Créer la base de données
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS agrimarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "[OK] Base de données 'agrimarket' créée\n";
    
    $pdo->exec("USE agrimarket");
    echo "[OK] Base de données sélectionnée\n\n";
} catch (PDOException $e) {
    die("[ERREUR] Création base de données: " . $e->getMessage() . "\n");
}

// Lire et exécuter le fichier SQL
$sqlFile = __DIR__ . '/../database.sql';

if (!file_exists($sqlFile)) {
    die("[ERREUR] Fichier database.sql non trouvé\n");
}

echo "Importation des tables...\n";

$sql = file_get_contents($sqlFile);
$statements = array_filter(array_map('trim', explode(';', $sql)));

$tableCount = 0;
foreach ($statements as $statement) {
    if (empty($statement) || stripos($statement, '--') === 0 || stripos($statement, '/*') !== false) {
        continue;
    }
    try {
        $pdo->exec($statement);
        if (preg_match('/CREATE TABLE (\w+)/i', $statement, $match)) {
            $tableCount++;
            echo "  - Table {$match[1]} créée\n";
        }
    } catch (PDOException $e) {
        // Ignorer les erreurs de duplication
        if (strpos($e->getMessage(), 'already exists') === false) {
            // Ignorer
        }
    }
}

echo "\n[OK] $tableCount tables créées\n";

// Insérer les données de test
echo "\nInsertion des données de test...\n";

try {
    // Catégories
    $pdo->exec("INSERT IGNORE INTO categories (id, nom, icone, description, ordre_affichage) VALUES 
        (1, 'Légumes', 'fa-leaf', 'Légumes frais du Cameroun', 1),
        (2, 'Fruits', 'fa-apple-alt', 'Fruits tropicaux', 2),
        (3, 'Céréales', 'fa-seedling', 'Maïs, riz, sorgho', 3),
        (4, 'Tubercules', 'fa-carrot', 'Manioc, igname, patate', 4),
        (5, 'Élevages', 'fa-paw', 'Volailles, porcs, chèvres', 5),
        (6, 'Poissons', 'fa-fish', 'Poissons d\\'élevage', 6)");
    echo "  - Catégories insérées\n";
    
    // Utilisateurs
    $pdo->exec("INSERT IGNORE INTO utilisateurs (id, nom, prenom, email, mot_de_passe, telephone, role, statut, region, ville, note_moyenne) VALUES 
        (1, 'Kouam', 'Jean', 'jean@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345678', 'agriculteur', 'actif', 'Ouest', 'Bafoussam', 4.5),
        (2, 'Tagne', 'Marie', 'marie@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345679', 'acheteur', 'actif', 'Centre', 'Yaoundé', 4.8),
        (3, 'AgriChem', 'SA', 'contact@agrichem.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345680', 'entreprise_phyto', 'actif', 'Littoral', 'Douala', 4.7),
        (4, 'Nguetch', 'Pierre', 'pierre@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345681', 'porteur_projet', 'actif', 'Centre', 'Yaoundé', 4.2),
        (5, 'InvestCorp', 'SARL', 'invest@investcorp.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345682', 'investisseur', 'actif', 'Littoral', 'Douala', 4.6),
        (6, 'Kamga', 'Express', 'contact@kamgaexpress.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345690', 'transporteur', 'actif', 'Littoral', 'Douala', 4.3)");
    echo "  - Utilisateurs insérés\n";
    
    // Produits
    $pdo->exec("INSERT IGNORE INTO produits (id, vendeur_id, categorie_id, nom, description, prix_unitaire, unite, quantite_disponible, region, ville, note_moyenne, nb_ventes) VALUES 
        (1, 1, 3, 'Maïs blanc', 'Maïs blanc de qualité supérieure', 3500, 'kg', 200, 'Ouest', 'Bafoussam', 4.5, 45),
        (2, 1, 1, 'Tomates fraîches', 'Tomates fraîches de Foumbot', 1200, 'bassin', 50, 'Ouest', 'Foumbot', 4.7, 32),
        (3, 1, 3, 'Arachides', 'Arachides de Garoua', 2800, 'kg', 150, 'Nord', 'Garoua', 4.2, 28),
        (4, 1, 4, 'Manioc frais', 'Manioc frais de Yaoundé', 800, 'kg', 300, 'Centre', 'Yaoundé', 4.6, 67),
        (5, 1, 2, 'Bananes plantains', 'Bananes plantains de Douala', 500, 'kg', 100, 'Littoral', 'Douala', 4.4, 23),
        (6, 1, 1, 'Feuille de manioc', 'Feuilles de manioc fraîches', 2500, 'fagot', 50, 'Ouest', 'Bafoussam', 4.3, 15),
        (7, 1, 5, 'Poulets de chair', 'Poulets élevés localement', 4500, 'poulet', 100, 'Centre', 'Yaoundé', 4.8, 56),
        (8, 1, 6, 'Tilapia frais', 'Tilapia d\\'élevage', 2000, 'kg', 80, 'Sud', 'Kribi', 4.5, 34)");
    echo "  - Produits insérés\n";
    
    // Produits phyto
    $pdo->exec("INSERT IGNORE INTO produits_phyto (id, entreprise_id, categorie, nom, description, prix, unite, marque, promo_pourcentage) VALUES 
        (1, 3, 'engrais', 'Engrais NPK 20-10-10', 'Engrais NPK pour toutes cultures', 18500, 'sac 50kg', 'AgriChem SA', 0),
        (2, 3, 'semences', 'Semences maize hybride', 'Semences à haut rendement (8t/ha)', 4200, 'kg', 'Semences Tropicales', 10),
        (3, 3, 'pesticides', 'Insecticide total Bio', 'Contre pucerons, chenilles, aleurodes', 8500, 'L', 'PhytoCam', 0),
        (4, 3, 'engrais', 'BioFert Organique', 'Compost biologique enrichi', 12000, 'sac 25kg', 'BioFert', 5),
        (5, 3, 'equipement', 'Kit irrigation goutte-à-goutte', 'Pour 500m² - Complete kit', 145000, 'kit', 'IrrigTech', 0),
        (6, 3, 'outillage', 'Déchaumeuse manuelle', 'Pour travail du sol', 35000, 'unité', 'AgriTool', 0)");
    echo "  - Produits phyto insérés\n";
    
    // Projets
    $pdo->exec("INSERT IGNORE INTO projets (id, porteur_id, titre, description, type_projet, budget_total, montant_collecte, taux_retour, duree_mois, localisation, region, statut) VALUES 
        (1, 4, 'Élevage poulets de chair', 'Élevage de 500 poulets de chair avec moderne bâtiment', 'elevage', 2500000, 1625000, 22.00, 12, 'Yaoundé', 'Centre', 'en_cours'),
        (2, 4, 'Transformation manioc en farine', 'Usine de transformation de manioc en farine de qualité supérieure', 'transformation', 5000000, 2000000, 18.00, 18, 'Bafoussam', 'Ouest', 'en_cours'),
        (3, 4, 'Pisciculture tilapia', 'Élevage de tilapia dans étangs modernes', 'pisciculture', 1800000, 1530000, 28.00, 8, 'Kribi', 'Sud', 'en_cours'),
        (4, 4, 'Culture maraîchère bio', 'Production de légumes biologiques sous serres', 'culture', 1200000, 450000, 15.00, 6, 'Douala', 'Littoral', 'en_cours')");
    echo "  - Projets insérés\n";
    
    // Transporteurs
    $pdo->exec("INSERT IGNORE INTO transporteurs (id, utilisateur_id, nom_entreprise, telephone, zone_service, tarif_km, note_moyenne) VALUES 
        (1, 6, 'Kamga Express', '+237612345690', '[\"Douala\",\"Yaoundé\",\"Bafoussam\"]', 45, 4.3),
        (2, 6, 'TransCam Logistique', '+237612345691', '[\"Douala\",\"Yaoundé\",\"Kribi\"]', 50, 4.5),
        (3, 6, 'RapidCargo', '+237612345692', '[\"Toutes régions\"]', 55, 4.1)");
    echo "  - Transporteurs insérés\n";
    
    // Paramètres
    $pdo->exec("INSERT IGNORE INTO parametres (cle, valeur, description) VALUES 
        ('commission_taux', '5', 'Taux de commission en pourcentage'),
        ('frais_livraison_base', '2500', 'Frais de livraison de base'),
        ('mobile_money_mtn', '+237612345678', 'Numéro MTN MoMo'),
        ('mobile_money_orange', '+237699999999', 'Numéro Orange Money'),
        ('email_support', 'support@agrimarket.cm', 'Email de support')");
    echo "  - Paramètres insérés\n";
    
} catch (PDOException $e) {
    echo "  [INFO] Données déjà existantes ou erreur: " . $e->getMessage() . "\n";
}

echo "\n============================================\n";
echo "  Installation terminée avec succès !\n";
echo "============================================\n\n";
echo "Vous pouvez maintenant accéder à:\n";
echo "  - Application: http://localhost/agrimarket\n";
echo "  - API: http://localhost/agrimarket/api\n";
echo "  - phpMyAdmin: http://localhost/phpmyadmin\n\n";
echo "Identifiants de test:\n";
echo "  - Email: jean@email.com\n";
echo "  - Mot de passe: password\n\n";
?>