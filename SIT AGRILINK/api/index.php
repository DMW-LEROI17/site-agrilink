<?php
/**
 * =====================================================
 * AGRIMARKET CAMEROUN - API PHP Complete
 * Version: 1.0.0
 * Date: 30 Avril 2026
 * =====================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// =====================================================
// CONFIGURATION BDD
// =====================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agrimarket');

$pdo = null;

function getDB() {
    global $pdo;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            $pdo = false;
        }
    }
    return $pdo;
}

// =====================================================
// DONNÉES EN MÉMOIRE
// =====================================================

$categories = [
    ['id'=>1, 'nom'=>'Légumes', 'icone'=>'fa-leaf', 'description'=>'Légumes frais du Cameroun'],
    ['id'=>2, 'nom'=>'Fruits', 'icone'=>'fa-apple-alt', 'description'=>'Fruits tropicaux'],
    ['id'=>3, 'nom'=>'Céréales', 'icone'=>'fa-seedling', 'description'=>'Maïs, riz, sorgho'],
    ['id'=>4, 'nom'=>'Tubercules', 'icone'=>'fa-carrot', 'description'=>'Manioc, igname, patate'],
    ['id'=>5, 'nom'=>'Élevages', 'icone'=>'fa-paw', 'description'=>'Volailles, porcs, chèvres'],
    ['id'=>6, 'nom'=>'Poissons', 'icone'=>'fa-fish', 'description'=>'Poissons d\'élevage']
];

$produits = [
    ['id'=>1, 'vendeur_id'=>1, 'categorie_id'=>3, 'nom'=>'Maïs blanc', 'description'=>'Maïs blanc de qualité supérieure', 'prix_unitaire'=>3500, 'unite'=>'kg', 'quantite_disponible'=>200, 'region'=>'Bafoussam', 'ville'=>'Bafoussam', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.5, 'nb_ventes'=>45],
    ['id'=>2, 'vendeur_id'=>1, 'categorie_id'=>1, 'nom'=>'Tomates fraîches', 'description'=>'Tomates fraîches de Foumbot', 'prix_unitaire'=>1200, 'unite'=>'bassin', 'quantite_disponible'=>50, 'region'=>'Ouest', 'ville'=>'Foumbot', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.7, 'nb_ventes'=>32],
    ['id'=>3, 'vendeur_id'=>1, 'categorie_id'=>3, 'nom'=>'Arachides', 'description'=>'Arachides de Garoua', 'prix_unitaire'=>2800, 'unite'=>'kg', 'quantite_disponible'=>150, 'region'=>'Nord', 'ville'=>'Garoua', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.2, 'nb_ventes'=>28],
    ['id'=>4, 'vendeur_id'=>1, 'categorie_id'=>4, 'nom'=>'Manioc frais', 'description'=>'Manioc frais de Yaoundé', 'prix_unitaire'=>800, 'unite'=>'kg', 'quantite_disponible'=>300, 'region'=>'Centre', 'ville'=>'Yaoundé', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.6, 'nb_ventes'=>67],
    ['id'=>5, 'vendeur_id'=>1, 'categorie_id'=>2, 'nom'=>'Bananes plantains', 'description'=>'Bananes plantains de Douala', 'prix_unitaire'=>500, 'unite'=>'kg', 'quantite_disponible'=>100, 'region'=>'Littoral', 'ville'=>'Douala', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.4, 'nb_ventes'=>23],
    ['id'=>6, 'vendeur_id'=>1, 'categorie_id'=>1, 'nom'=>'Feuille de manioc', 'description'=>'Feuilles de manioc fraîches', 'prix_unitaire'=>2500, 'unite'=>'fagot', 'quantite_disponible'=>50, 'region'=>'Ouest', 'ville'=>'Bafoussam', 'statut_stock'=>'stock_limite', 'note_moyenne'=>4.3, 'nb_ventes'=>15],
    ['id'=>7, 'vendeur_id'=>1, 'categorie_id'=>5, 'nom'=>'Poulets de chair', 'description'=>'Poulets élevés localement', 'prix_unitaire'=>4500, 'unite'=>'poulet', 'quantite_disponible'=>100, 'region'=>'Centre', 'ville'=>'Yaoundé', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.8, 'nb_ventes'=>56],
    ['id'=>8, 'vendeur_id'=>1, 'categorie_id'=>6, 'nom'=>'Tilapia frais', 'description'=>'Tilapia d\'élevage', 'prix_unitaire'=>2000, 'unite'=>'kg', 'quantite_disponible'=>80, 'region'=>'Sud', 'ville'=>'Kribi', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.5, 'nb_ventes'=>34]
];

$produitsPhyto = [
    ['id'=>1, 'entreprise_id'=>2, 'categorie'=>'engrais', 'nom'=>'Engrais NPK 20-10-10', 'description'=>'Engrais NPK pour toutes cultures', 'prix'=>18500, 'unite'=>'sac 50kg', 'marque'=>'AgriChem SA', 'promo_pourcentage'=>0],
    ['id'=>2, 'entreprise_id'=>2, 'categorie'=>'semences', 'nom'=>'Semences maize hybride', 'description'=>'Semences à haut rendement (8t/ha)', 'prix'=>4200, 'unite'=>'kg', 'marque'=>'Semences Tropicales', 'promo_pourcentage'=>10],
    ['id'=>3, 'entreprise_id'=>2, 'categorie'=>'pesticides', 'nom'=>'Insecticide total Bio', 'description'=>'Contre pucerons, chenilles, aleurodes', 'prix'=>8500, 'unite'=>'L', 'marque'=>'PhytoCam', 'promo_pourcentage'=>0],
    ['id'=>4, 'entreprise_id'=>2, 'categorie'=>'engrais', 'nom'=>'BioFert Organique', 'description'=>'Compost biologique enrichi', 'prix'=>12000, 'unite'=>'sac 25kg', 'marque'=>'BioFert', 'promo_pourcentage'=>5],
    ['id'=>5, 'entreprise_id'=>2, 'categorie'=>'equipement', 'nom'=>'Kit irrigation goutte-à-goutte', 'description'=>'Pour 500m² - Complete kit', 'prix'=>145000, 'unite'=>'kit', 'marque'=>'IrrigTech', 'promo_pourcentage'=>0],
    ['id'=>6, 'entreprise_id'=>2, 'categorie'=>'outillage', 'nom'=>'Déchaumeuse manuelle', 'description'=>'Pour travail du sol', 'prix'=>35000, 'unite'=>'unité', 'marque'=>'AgriTool', 'promo_pourcentage'=>0]
];

$users = [
    ['id'=>1, 'nom'=>'Kouam', 'prenom'=>'Jean', 'email'=>'jean@email.com', 'telephone'=>'+237612345678', 'role'=>'agriculteur', 'statut'=>'actif', 'region'=>'Ouest', 'ville'=>'Bafoussam', 'note_moyenne'=>4.5],
    ['id'=>2, 'nom'=>'Tagne', 'prenom'=>'Marie', 'email'=>'marie@email.com', 'telephone'=>'+237612345679', 'role'=>'acheteur', 'statut'=>'actif', 'region'=>'Centre', 'ville'=>'Yaoundé', 'note_moyenne'=>4.8],
    ['id'=>3, 'nom'=>'AgriChem', 'prenom'=>'SA', 'email'=>'contact@agrichem.cm', 'telephone'=>'+237612345680', 'role'=>'entreprise_phyto', 'statut'=>'actif', 'region'=>'Littoral', 'ville'=>'Douala', 'note_moyenne'=>4.7],
    ['id'=>4, 'nom'=>'Nguetch', 'prenom'=>'Pierre', 'email'=>'pierre@email.com', 'telephone'=>'+237612345681', 'role'=>'porteur_projet', 'statut'=>'actif', 'region'=>'Centre', 'ville'=>'Yaoundé', 'note_moyenne'=>4.2]
];

$projets = [
    ['id'=>1, 'porteur_id'=>4, 'titre'=>'Élevage poulets de chair', 'description'=>'Élevage de 500 poulets de chair avec moderne bâtiment', 'type_projet'=>'elevage', 'budget_total'=>2500000, 'montant_collecte'=>1625000, 'taux_retour'=>22, 'duree_mois'=>12, 'localisation'=>'Yaoundé', 'region'=>'Centre', 'statut'=>'en_cours'],
    ['id'=>2, 'porteur_id'=>4, 'titre'=>'Transformation manioc en farine', 'description'=>'Usine de transformation de manioc en farine', 'type_projet'=>'transformation', 'budget_total'=>5000000, 'montant_collecte'=>2000000, 'taux_retour'=>18, 'duree_mois'=>18, 'localisation'=>'Bafoussam', 'region'=>'Ouest', 'statut'=>'en_cours'],
    ['id'=>3, 'porteur_id'=>4, 'titre'=>'Pisciculture tilapia', 'description'=>'Élevage de tilapia dans étangs modernes', 'type_projet'=>'pisciculture', 'budget_total'=>1800000, 'montant_collecte'=>1530000, 'taux_retour'=>28, 'duree_mois'=>8, 'localisation'=>'Kribi', 'region'=>'Sud', 'statut'=>'en_cours'],
    ['id'=>4, 'porteur_id'=>4, 'titre'=>'Culture maraîchère bio', 'description'=>'Production de légumes biologiques sous serres', 'type_projet'=>'culture', 'budget_total'=>1200000, 'montant_collecte'=>450000, 'taux_retour'=>15, 'duree_mois'=>6, 'localisation'=>'Douala', 'region'=>'Littoral', 'statut'=>'en_cours']
];

$commandes = [];
$notifications = [
    ['id'=>1, 'user_id'=>1, 'type'=>'commande', 'titre'=>'Nouvelle commande', 'message'=>'Vous avez reçu une commande de 35 000 FCAF', 'lu'=>false],
    ['id'=>2, 'user_id'=>1, 'type'=>'paiement', 'titre'=>'Paiement reçu', 'message'=>'Paiement de 18 500 FCAF confirmé', 'lu'=>false],
    ['id'=>3, 'user_id'=>1, 'type'=>'livraison', 'titre'=>'Livraison effectuée', 'message'=>'Commande #CMD-001 livrée', 'lu'=>true]
];

$transporteurs = [
    ['id'=>1, 'nom_entreprise'=>'Kamga Express', 'telephone'=>'+237612345690', 'zone_service'=>['Douala','Yaoundé','Bafoussam'], 'tarif_km'=>45, 'note_moyenne'=>4.3],
    ['id'=>2, 'nom_entreprise'=>'TransCam Logistique', 'telephone'=>'+237612345691', 'zone_service'=>['Douala','Yaoundé','Kribi'], 'tarif_km'=>50, 'note_moyenne'=>4.5],
    ['id'=>3, 'nom_entreprise'=>'RapidCargo', 'telephone'=>'+237612345692', 'zone_service'=>['Toutes régions'], 'tarif_km'=>55, 'note_moyenne'=>4.1]
];

// =====================================================
// ROUTEUR
// =====================================================

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = str_replace('/api/', '', $path);
$path = trim($path, '/');

$parts = explode('/', $path);
$ressource = $parts[0] ?? '';

// =====================================================
// FONCTIONS
// =====================================================

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

function getAuthUser() {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? $headers['authorization'] ?? null;
    if ($token) {
        return ['id'=>1, 'nom'=>'Kouam', 'prenom'=>'Jean', 'role'=>'agriculteur'];
    }
    return null;
}

// =====================================================
// ENDPOINTS
// =====================================================

switch ($ressource) {
    
    case '':
    case 'health':
        jsonResponse([
            'success'=>true, 
            'message'=>'API AgriMarket Cameroun', 
            'version'=>'1.0.0',
            'timestamp'=>date('c'),
            'endpoints'=>['GET /produits', 'GET /categories', 'GET /phyto', 'GET /projets', 'POST /commandes', 'GET /commandes', 'POST /projets/investir', 'GET /transporteurs', 'POST /transport/calcul', 'GET /notifications', 'POST /users/register', 'POST /users/login', 'GET /stats']
        ]);
        break;

    case 'categories':
        if ($method === 'GET') {
            jsonResponse(['success'=>true, 'data'=>$categories]);
        }
        break;

    case 'produits':
        if ($method === 'GET') {
            $categorie = $_GET['categorie'] ?? null;
            $region = $_GET['region'] ?? null;
            $search = $_GET['search'] ?? null;
            
            $filtered = $produits;
            if ($categorie) $filtered = array_filter($filtered, fn($p) => $p['categorie_id'] == $categorie);
            if ($region) $filtered = array_filter($filtered, fn($p) => stripos($p['region'], $region) !== false);
            if ($search) $filtered = array_filter($filtered, fn($p) => stripos($p['nom'], $search) !== false || stripos($p['description'], $search) !== false);
            
            jsonResponse(['success'=>true, 'data'=>array_values($filtered), 'total'=>count($filtered)]);
        }
        break;

    case 'phyto':
    case 'produits-phyto':
        if ($method === 'GET') {
            $categorie = $_GET['categorie'] ?? null;
            $filtered = $categorie ? array_filter($produitsPhyto, fn($p) => $p['categorie'] === $categorie) : $produitsPhyto;
            jsonResponse(['success'=>true, 'data'=>array_values($filtered)]);
        }
        break;

    case 'projets':
        if ($method === 'GET') {
            $type = $_GET['type'] ?? null;
            $filtered = $type ? array_filter($projets, fn($p) => $p['type_projet'] === $type) : $projets;
            jsonResponse(['success'=>true, 'data'=>array_values($filtered)]);
        }
        elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'investir') {
            $input = json_decode(file_get_contents('php://input'), true);
            $projetId = $input['projet_id'] ?? 0;
            $montant = $input['montant'] ?? 0;
            
            foreach ($projets as &$p) {
                if ($p['id'] == $projetId) {
                    $p['montant_collecte'] += $montant;
                    jsonResponse(['success'=>true, 'message'=>'Investissement enregistré', 'data'=>$p]);
                    break;
                }
            }
            jsonResponse(['success'=>false, 'message'=>'Projet non trouvé'], 404);
        }
        break;

    case 'commandes':
        if ($method === 'GET') {
            jsonResponse(['success'=>true, 'data'=>$commandes]);
        }
        elseif ($method === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $items = $input['items'] ?? [];
            
            $sous_total = array_reduce($items, fn($sum, $item) => $sum + ($item['prix'] * $item['quantite']), 0);
            $frais_livraison = 2500;
            $montant_total = $sous_total + $frais_livraison;
            
            $commande = [
                'id' => count($commandes) + 1,
                'numero_commande' => 'CMD-' . date('YmdHis'),
                'statut' => 'en_attente',
                'sous_total' => $sous_total,
                'frais_livraison' => $frais_livraison,
                'montant_total' => $montant_total,
                'mode_paiement' => $input['mode_paiement'] ?? 'mtn_momo',
                'items' => $items,
                'date_creation' => date('c')
            ];
            
            $commandes[] = $commande;
            jsonResponse(['success'=>true, 'message'=>'Commande créée', 'data'=>$commande]);
        }
        break;

    case 'transporteurs':
        if ($method === 'GET') {
            jsonResponse(['success'=>true, 'data'=>$transporteurs]);
        }
        break;

    case 'transport':
        if ($method === 'POST' && isset($parts[1]) && $parts[1] === 'calcul') {
            $input = json_decode(file_get_contents('php://input'), true);
            $from = $input['from'] ?? '';
            $to = $input['to'] ?? '';
            $weight = $input['weight'] ?? 0;
            
            $distances = [
                'douala-yaounde'=>250, 'yaounde-douala'=>250,
                'douala-bafoussam'=>320, 'bafoussam-douala'=>320,
                'yaounde-bafoussam'=>180, 'bafoussam-yaounde'=>180,
                'douala-kribi'=>150, 'kribi-douala'=>150
            ];
            
            $route = strtolower($from) . '-' . strtolower($to);
            $distance = $distances[$route] ?? 100;
            $price = round($distance * $weight * 0.05);
            
            jsonResponse([
                'success'=>true, 
                'data'=>['distance'=>$distance, 'weight'=>$weight, 'price'=>max(2500, $price), 'duree_estimee'=>round($distance / 80 * 60) . ' min']
            ]);
        }
        break;

    case 'notifications':
        if ($method === 'GET') {
            $user = getAuthUser();
            $userId = $user ? $user['id'] : 1;
            $userNotifs = array_filter($notifications, fn($n) => $n['user_id'] == $userId);
            jsonResponse(['success'=>true, 'data'=>array_values($userNotifs), 'non_lues'=>count(array_filter($userNotifs, fn($n) => !$n['lu']))]);
        }
        break;

    case 'users':
        if ($method === 'POST' && isset($parts[1]) && $parts[1] === 'register') {
            $input = json_decode(file_get_contents('php://input'), true);
            $newUser = [
                'id' => count($users) + 1,
                'nom' => $input['nom'] ?? '',
                'prenom' => $input['prenom'] ?? '',
                'email' => $input['email'] ?? '',
                'telephone' => $input['telephone'] ?? '',
                'role' => $input['role'] ?? 'acheteur',
                'statut' => 'en_attente',
                'region' => $input['region'] ?? '',
                'ville' => $input['ville'] ?? ''
            ];
            $users[] = $newUser;
            jsonResponse(['success'=>true, 'message'=>'Compte créé avec succès', 'data'=>$newUser]);
        }
        elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'login') {
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $input['email'] ?? '';
            
            $user = array_filter($users, fn($u) => $u['email'] === $email);
            if ($user) {
                $userData = array_values($user)[0];
                jsonResponse(['success'=>true, 'message'=>'Connexion réussie', 'data'=>$userData, 'token'=>'demo_token_' . time()]);
            } else {
                jsonResponse(['success'=>false, 'message'=>'Email ou mot de passe incorrect'], 401);
            }
        }
        elseif ($method === 'GET') {
            jsonResponse(['success'=>true, 'data'=>$users]);
        }
        break;

    case 'stats':
    case 'statistiques':
        jsonResponse([
            'success'=>true,
            'data'=>[
                'utilisateurs_actifs'=>count(array_filter($users, fn($u) => $u['statut'] === 'actif')),
                'produits_actifs'=>count($produits),
                'commandes_mois'=>count($commandes),
                'projets_en_cours'=>count(array_filter($projets, fn($p) => $p['statut'] === 'en_cours')),
                'montant_total_projets'=>array_sum(array_column($projets, 'montant_collecte')),
                'transporteurs'=>count($transporteurs)
            ]
        ]);
        break;

    default:
        jsonResponse([
            'success'=>false, 
            'message'=>'Endpoint non trouvé', 
            'path'=>$ressource,
            'suggestions'=>['produits', 'categories', 'phyto', 'projets', 'commandes', 'transporteurs', 'notifications', 'users', 'stats']
        ], 404);
}