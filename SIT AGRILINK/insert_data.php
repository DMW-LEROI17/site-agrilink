<?php
/**
 * AGRIMARKET CAMEROUN - Script d'insertion des données
 * Exécuter ce fichier pour ajouter les données dans la base
 */

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'agrimarket';

echo "============================================\n";
echo "  AGRIMARKET - Insertion des données\n";
echo "============================================\n\n";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "[OK] Connexion à la base '$dbName' réussie\n\n";
} catch (PDOException $e) {
    die("[ERREUR] Connexion échouée: " . $e->getMessage() . "\n");
}

// =====================================================
// INSERTION DES DONNÉES
// =====================================================

echo "Insertion des catégories...\n";
$pdo->exec("INSERT INTO categories (nom, icone, description, ordre_affichage) VALUES 
    ('Légumes', 'fa-leaf', 'Légumes frais du Cameroun', 1),
    ('Fruits', 'fa-apple-alt', 'Fruits tropicaux', 2),
    ('Céréales', 'fa-seedling', 'Maïs, riz, sorgho', 3),
    ('Tubercules', 'fa-carrot', 'Manioc, igname, patate', 4),
    ('Élevages', 'fa-paw', 'Volailles, porcs, chèvres', 5),
    ('Poissons', 'fa-fish', 'Poissons d\\'élevage', 6)
ON DUPLICATE KEY UPDATE nom=VALUES(nom)");
echo "  ✓ 6 catégories\n";

echo "Insertion des utilisateurs...\n";
$pdo->exec("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, statut, region, ville, note_moyenne) VALUES 
    ('Kouam', 'Jean', 'jean@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345678', 'agriculteur', 'actif', 'Ouest', 'Bafoussam', 4.5),
    ('Tagne', 'Marie', 'marie@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345679', 'acheteur', 'actif', 'Centre', 'Yaoundé', 4.8),
    ('AgriChem', 'SA', 'contact@agrichem.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345680', 'entreprise_phyto', 'actif', 'Littoral', 'Douala', 4.7),
    ('Nguetch', 'Pierre', 'pierre@email.com', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345681', 'porteur_projet', 'actif', 'Centre', 'Yaoundé', 4.2),
    ('InvestCorp', 'SARL', 'invest@investcorp.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345682', 'investisseur', 'actif', 'Littoral', 'Douala', 4.6),
    ('Kamga', 'Express', 'contact@kamgaexpress.cm', '\$2y\$10\$abcdefghijklmnopqrstuv', '+237612345690', 'transporteur', 'actif', 'Littoral', 'Douala', 4.3)
ON DUPLICATE KEY UPDATE nom=VALUES(nom)");
echo "  ✓ 6 utilisateurs\n";

echo "Insertion des produits agricoles...\n";
$pdo->exec("INSERT INTO produits (vendeur_id, categorie_id, nom, description, prix_unitaire, unite, quantite_disponible, region, ville, note_moyenne, nb_ventes) VALUES 
    (1, 3, 'Maïs blanc', 'Maïs blanc de qualité supérieure', 3500, 'kg', 200, 'Ouest', 'Bafoussam', 4.5, 45),
    (1, 1, 'Tomates fraîches', 'Tomates fraîches de Foumbot', 1200, 'bassin', 50, 'Ouest', 'Foumbot', 4.7, 32),
    (1, 3, 'Arachides', 'Arachides de Garoua', 2800, 'kg', 150, 'Nord', 'Garoua', 4.2, 28),
    (1, 4, 'Manioc frais', 'Manioc frais de Yaoundé', 800, 'kg', 300, 'Centre', 'Yaoundé', 4.6, 67),
    (1, 2, 'Bananes plantains', 'Bananes plantains de Douala', 500, 'kg', 100, 'Littoral', 'Douala', 4.4, 23),
    (1, 1, 'Feuille de manioc', 'Feuilles de manioc fraîches', 2500, 'fagot', 50, 'Ouest', 'Bafoussam', 4.3, 15),
    (1, 5, 'Poulets de chair', 'Poulets élevés localement', 4500, 'poulet', 100, 'Centre', 'Yaoundé', 4.8, 56),
    (1, 6, 'Tilapia frais', 'Tilapia d\\'élevage', 2000, 'kg', 80, 'Sud', 'Kribi', 4.5, 34)
ON DUPLICATE KEY UPDATE nom=VALUES(nom)");
echo "  ✓ 8 produits\n";

echo "Insertion des produits phyto-sanitaires...\n";
$pdo->exec("INSERT INTO produits_phyto (entreprise_id, categorie, nom, description, prix, unite, marque, promo_pourcentage) VALUES 
    (3, 'engrais', 'Engrais NPK 20-10-10', 'Engrais NPK pour toutes cultures', 18500, 'sac 50kg', 'AgriChem SA', 0),
    (3, 'semences', 'Semences maize hybride', 'Semences à haut rendement (8t/ha)', 4200, 'kg', 'Semences Tropicales', 10),
    (3, 'pesticides', 'Insecticide total Bio', 'Contre pucerons, chenilles, aleurodes', 8500, 'L', 'PhytoCam', 0),
    (3, 'engrais', 'BioFert Organique', 'Compost biologique enrichi', 12000, 'sac 25kg', 'BioFert', 5),
    (3, 'equipement', 'Kit irrigation goutte-à-goutte', 'Pour 500m² - Complete kit', 145000, 'kit', 'IrrigTech', 0),
    (3, 'outillage', 'Déchaumeuse manuelle', 'Pour travail du sol', 35000, 'unité', 'AgriTool', 0)
ON DUPLICATE KEY UPDATE nom=VALUES(nom)");
echo "  ✓ 6 produits phyto\n";

echo "Insertion des projets agricoles...\n";
$pdo->exec("INSERT INTO projets (porteur_id, titre, description, type_projet, budget_total, montant_collecte, taux_retour, duree_mois, localisation, region, statut) VALUES 
    (4, 'Élevage poulets de chair', 'Élevage de 500 poulets de chair avec moderne bâtiment', 'elevage', 2500000, 1625000, 22.00, 12, 'Yaoundé', 'Centre', 'en_cours'),
    (4, 'Transformation manioc en farine', 'Usine de transformation de manioc en farine de qualité supérieure', 'transformation', 5000000, 2000000, 18.00, 18, 'Bafoussam', 'Ouest', 'en_cours'),
    (4, 'Pisciculture tilapia', 'Élevage de tilapia dans étangs modernes', 'pisciculture', 1800000, 1530000, 28.00, 8, 'Kribi', 'Sud', 'en_cours'),
    (4, 'Culture maraîchère bio', 'Production de légumes biologiques sous serres', 'culture', 1200000, 450000, 15.00, 6, 'Douala', 'Littoral', 'en_cours')
ON DUPLICATE KEY UPDATE titre=VALUES(titre)");
echo "  ✓ 4 projets\n";

echo "Insertion des transporteurs...\n";
$pdo->exec("INSERT INTO transporteurs (utilisateur_id, nom_entreprise, telephone, zone_service, tarif_km, note_moyenne) VALUES 
    (6, 'Kamga Express', '+237612345690', '[\"Douala\",\"Yaoundé\",\"Bafoussam\"]', 45, 4.3),
    (6, 'TransCam Logistique', '+237612345691', '[\"Douala\",\"Yaoundé\",\"Kribi\"]', 50, 4.5),
    (6, 'RapidCargo', '+237612345692', '[\"Toutes régions\"]', 55, 4.1)
ON DUPLICATE KEY UPDATE nom_entreprise=VALUES(nom_entreprise)");
echo "  ✓ 3 transporteurs\n";

echo "Insertion des paramètres...\n";
$pdo->exec("INSERT INTO parametres (cle, valeur, description) VALUES 
    ('commission_taux', '5', 'Taux de commission en pourcentage'),
    ('frais_livraison_base', '2500', 'Frais de livraison de base'),
    ('mobile_money_mtn', '+237612345678', 'Numéro MTN MoMo'),
    ('mobile_money_orange', '+237699999999', 'Numéro Orange Money'),
    ('email_support', 'support@agrimarket.cm', 'Email de support')
ON DUPLICATE KEY UPDATE valeur=VALUES(valeur)");
echo "  ✓ 5 paramètres\n";

echo "Insertion des notifications...\n";
$pdo->exec("INSERT INTO notifications (utilisateur_id, type, titre, message, lu) VALUES 
    (1, 'commande', 'Nouvelle commande', 'Vous avez reçu une commande de 35 000 FCAF', 0),
    (1, 'paiement', 'Paiement reçu', 'Paiement de 18 500 FCAF confirmé', 0),
    (1, 'livraison', 'Livraison effectuée', 'Commande #CMD-001 livrée', 1)
ON DUPLICATE KEY UPDATE titre=VALUES(titre)");
echo "  ✓ 3 notifications\n";

echo "\n============================================\n";
echo "  ✅ Données insérées avec succès !\n";
echo "============================================\n\n";

// Afficher le résumé
$tables = ['categories', 'utilisateurs', 'produits', 'produits_phyto', 'projets', 'transporteurs', 'parametres', 'notifications'];
echo "Résumé des données:\n";
foreach ($tables as $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "  - $table: $count enregistrements\n";
    } catch (PDOException $e) {
        echo "  - $table: erreur\n";
    }
}

echo "\n🌾 AgriMarket Cameroun est prêt !\n";
echo "Accédez à: http://localhost/agrimarket/\n";
?>