<?php
// =====================================================
// AGRIMARKET CAMEROUN - API PHP Améliorée
// =====================================================

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$config = loadEnv(__DIR__ . '/.env');
$jwtSecret = $config['JWT_SECRET'] ?? 'agrimarket_secret_key_2024';

dataPath(__DIR__ . '/data');
$commandesFile = __DIR__ . '/data/commandes.json';
$usersFile = __DIR__ . '/data/users.json';

$commandes = file_exists($commandesFile) ? json_decode(file_get_contents($commandesFile), true) : [];
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

if (empty($users)) {
    $users = [
        [
            'id' => 1,
            'email' => 'admin@agrimarket.cm',
            'nom' => 'Admin AgriMarket',
            'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'role' => 'admin'
        ]
    ];
    file_put_contents($usersFile, json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$produits = [
    ['id'=>1, 'vendor_id'=>1, 'categorie_id'=>3, 'nom'=>'Maïs blanc', 'description'=>'Maïs blanc de qualité supérieure', 'prix_unitaire'=>3500, 'unite'=>'kg', 'quantite_disponible'=>200, 'region'=>'Bafoussam', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.5, 'nb_ventes'=>45],
    ['id'=>2, 'vendor_id'=>1, 'categorie_id'=>1, 'nom'=>'Tomates fraîches', 'description'=>'Tomates fraîches de Foumbot', 'prix_unitaire'=>1200, 'unite'=>'bassin', 'quantite_disponible'=>50, 'region'=>'Foumbot', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.7, 'nb_ventes'=>32],
    ['id'=>3, 'vendor_id'=>1, 'categorie_id'=>3, 'nom'=>'Arachides', 'description'=>'Arachides de Garoua', 'prix_unitaire'=>2800, 'unite'=>'kg', 'quantite_disponible'=>150, 'region'=>'Garoua', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.2, 'nb_ventes'=>28],
    ['id'=>4, 'vendor_id'=>1, 'categorie_id'=>4, 'nom'=>'Manioc frais', 'description'=>'Manioc frais de Yaoundé', 'prix_unitaire'=>800, 'unite'=>'kg', 'quantite_disponible'=>300, 'region'=>'Yaoundé', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.6, 'nb_ventes'=>67],
    ['id'=>5, 'vendor_id'=>1, 'categorie_id'=>2, 'nom'=>'Bananes plantains', 'description'=>'Bananes plantains de Douala', 'prix_unitaire'=>500, 'unite'=>'kg', 'quantite_disponible'=>100, 'region'=>'Douala', 'statut_stock'=>'en_stock', 'note_moyenne'=>4.4, 'nb_ventes'=>23],
    ['id'=>6, 'vendor_id'=>1, 'categorie_id'=>1, 'nom'=>'Feuille de manioc', 'description'=>'Feuilles de manioc fraîches', 'prix_unitaire'=>2500, 'unite'=>'fagot', 'quantite_disponible'=>50, 'region'=>'Bafoussam', 'statut_stock'=>'stock_limite', 'note_moyenne'=>4.3, 'nb_ventes'=>15]
];

$produitsPhyto = [
    ['id'=>1, 'vendor_id'=>1, 'categorie'=>'engrais', 'nom'=>'Engrais NPK 20-10-10', 'description'=>'Engrais NPK pour cultures', 'prix'=>18500, 'unite'=>'sac 50kg', 'marque'=>'AgriChem', 'promo_pourcentage'=>0],
    ['id'=>2, 'vendor_id'=>1, 'categorie'=>'semences', 'nom'=>'Semences maize hybride', 'description'=>'Semences à haut rendement', 'prix'=>4200, 'unite'=>'kg', 'marque'=>'Semences Tropicales', 'promo_pourcentage'=>10],
    ['id'=>3, 'vendor_id'=>1, 'categorie'=>'pesticides', 'nom'=>'Insecticide total', 'description'=>'Contre pucerons et chenilles', 'prix'=>8500, 'unite'=>'L', 'marque'=>'PhytoCam', 'promo_pourcentage'=>0],
    ['id'=>4, 'vendor_id'=>1, 'categorie'=>'engrais', 'nom'=>'BioFert Organique', 'description'=>'Compost biologique', 'prix'=>12000, 'unite'=>'sac 25kg', 'marque'=>'BioFert', 'promo_pourcentage'=>0],
    ['id'=>5, 'vendor_id'=>1, 'categorie'=>'equipement', 'nom'=>'Kit irrigation goutte-à-goutte', 'description'=>'Pour 500m²', 'prix'=>145000, 'unite'=>'kit', 'marque'=>'IrrigTech', 'promo_pourcentage'=>0]
];

$categories = [
    ['id'=>1, 'nom'=>'Légumes', 'icone'=>'fa-leaf'],
    ['id'=>2, 'nom'=>'Fruits', 'icone'=>'fa-apple-alt'],
    ['id'=>3, 'nom'=>'Céréales', 'icone'=>'fa-seedling'],
    ['id'=>4, 'nom'=>'Tubercules', 'icone'=>'fa-carrot']
];

$projets = [
    ['id'=>1, 'createur_id'=>1, 'titre'=>'Élevage poulets de chair', 'description'=>'Élevage de 500 poulets', 'type_projet'=>'elevage', 'budget_total'=>2500000, 'montant_collecte'=>1625000, 'taux_retour'=>22, 'duree_mois'=>12, 'localisation'=>'Yaoundé', 'statut'=>'en_cours'],
    ['id'=>2, 'createur_id'=>1, 'titre'=>'Transformation manioc en farine', 'description'=>'Usine de transformation', 'type_projet'=>'transformation', 'budget_total'=>5000000, 'montant_collecte'=>2000000, 'taux_retour'=>18, 'duree_mois'=>18, 'localisation'=>'Bafoussam', 'statut'=>'en_cours'],
    ['id'=>3, 'createur_id'=>1, 'titre'=>'Pisciculture tilapia', 'description'=>'Élevage de tilapia', 'type_projet'=>'pisciculture', 'budget_total'=>1800000, 'montant_collecte'=>1530000, 'taux_retour'=>28, 'duree_mois'=>8, 'localisation'=>'Kribi', 'statut'=>'en_cours']
];

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (!empty($_GET['path'])) {
    $path = trim($_GET['path'], '/');
} else {
    $path = parse_url($uri, PHP_URL_PATH);
    $path = preg_replace('#^(.*/index\.php)#', '', $path);
    $path = trim($path, '/');
}

if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

switch (true) {
    case ($path === '' || $path === 'health') && $method === 'GET':
        jsonResponse(['success' => true, 'message' => 'API AgriMarket Cameroun', 'version' => '1.0.0', 'timestamp' => date('c')]);
        break;

    case $path === 'db/test' && $method === 'GET':
        $db = testDatabaseConnection($config);
        jsonResponse(['success' => true, 'database' => $db]);
        break;

    case $path === 'auth/register' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $nom = trim($input['nom'] ?? '');
        if (!$email || !$password || !$nom) {
            http_response_code(422);
            jsonResponse(['success' => false, 'message' => 'email, nom et password requis']);
        }
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                http_response_code(409);
                jsonResponse(['success' => false, 'message' => 'Utilisateur déjà existant']);
            }
        }
        $nouvelUtilisateur = [
            'id' => count($users) + 1,
            'email' => $email,
            'nom' => $nom,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        $users[] = $nouvelUtilisateur;
        file_put_contents($usersFile, json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        unset($nouvelUtilisateur['password']);
        jsonResponse(['success' => true, 'message' => 'Inscription réussie', 'user' => $nouvelUtilisateur]);
        break;

    case $path === 'auth/login' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $token = jwtEncode(['sub' => $user['id'], 'email' => $user['email'], 'role' => $user['role']]);
                jsonResponse(['success' => true, 'message' => 'Connexion réussie', 'token' => $token, 'user' => ['id' => $user['id'], 'email' => $user['email'], 'nom' => $user['nom'], 'role' => $user['role']]]);
            }
        }
        http_response_code(401);
        jsonResponse(['success' => false, 'message' => 'Identifiants invalides']);
        break;

    case $path === 'auth/profile' && $method === 'GET':
        $user = requireAuth($jwtSecret, $users);
        unset($user['password']);
        jsonResponse(['success' => true, 'user' => $user]);
        break;

    case $path === 'admin/dashboard' && $method === 'GET':
        $user = requireAuth($jwtSecret, $users);
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            jsonResponse(['success' => false, 'message' => 'Accès admin nécessaire']);
        }
        jsonResponse(['success' => true, 'data' => [
            'produits' => count($produits),
            'commandes' => count($commandes),
            'clients' => count($users),
            'projets' => count($projets),
            'categories' => count($categories)
        ]]);
        break;

    case $path === 'admin/export/commandes' && $method === 'GET':
        $user = requireAuth($jwtSecret, $users);
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            jsonResponse(['success' => false, 'message' => 'Accès admin nécessaire']);
        }
        exportCommandesCsv($commandes);
        break;

    case $path === 'payments/momo' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $provider = $input['provider'] ?? 'mtn';
        $amount = $input['amount'] ?? 0;
        $phone = $input['phone'] ?? '';
        $external_id = $input['external_id'] ?? 'CMD-' . time();
        if (!$phone || !$amount) {
            http_response_code(422);
            jsonResponse(['success' => false, 'message' => 'phone et amount requis']);
        }
        $result = processmomoPayment($provider, $phone, $amount, $external_id, $config);
        if ($result['success']) {
            jsonResponse(['success' => true, 'message' => 'Paiement Mobile Money initié', 'data' => $result['data']]);
        } else {
            http_response_code(400);
            jsonResponse(['success' => false, 'message' => $result['error']]);
        }
        break;

    case $path === 'payments/stripe' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $amount = $input['amount'] ?? 0;
        $token = $input['token'] ?? '';
        $currency = $input['currency'] ?? 'XAF';
        $description = $input['description'] ?? 'Achat AgriMarket';
        if (!$token || !$amount) {
            http_response_code(422);
            jsonResponse(['success' => false, 'message' => 'token et amount requis']);
        }
        $result = processStripePayment($amount, $token, $currency, $description, $config);
        if ($result['success']) {
            jsonResponse(['success' => true, 'message' => 'Paiement Stripe traité', 'data' => $result['data']]);
        } else {
            http_response_code(400);
            jsonResponse(['success' => false, 'message' => $result['error']]);
        }
        break;

    case $path === 'notifications/email' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $to = trim($input['to'] ?? '');
        $subject = $input['subject'] ?? '';
        $body = $input['body'] ?? '';
        if (!$to || !$subject) {
            http_response_code(422);
            jsonResponse(['success' => false, 'message' => 'to et subject requis']);
        }
        $result = sendEmailNotification($to, $subject, $body, $config);
        jsonResponse(['success' => $result['success'], 'message' => $result['message'], 'data' => ['to' => $to]]);
        break;

    case $path === 'notifications/sms' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $to = trim($input['to'] ?? '');
        $text = $input['text'] ?? '';
        if (!$to || !$text) {
            http_response_code(422);
            jsonResponse(['success' => false, 'message' => 'to et text requis']);
        }
        $result = sendSmsNotification($to, $text, $config);
        jsonResponse(['success' => $result['success'], 'message' => $result['message'], 'data' => ['to' => $to]]);
        break;

    case $path === 'produits' && $method === 'GET':
        jsonResponse(['success' => true, 'data' => $produits]);
        break;

    case preg_match('#^produits/(\d+)$#', $path, $m) && $method === 'GET':
        $produit = array_filter($produits, fn($p) => $p['id'] == $m[1]);
        if ($produit) {
            jsonResponse(['success' => true, 'data' => array_values($produit)[0]]);
        }
        http_response_code(404);
        jsonResponse(['success' => false, 'message' => 'Produit non trouvé']);
        break;

    case $path === 'categories' && $method === 'GET':
        jsonResponse(['success' => true, 'data' => $categories]);
        break;

    case $path === 'phyto' && $method === 'GET':
        jsonResponse(['success' => true, 'data' => $produitsPhyto]);
        break;

    case $path === 'projets' && $method === 'GET':
        jsonResponse(['success' => true, 'data' => $projets]);
        break;

    case $path === 'projets/investir' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $projetId = $input['projet_id'] ?? 0;
        $montant = $input['montant'] ?? 0;
        foreach ($projets as &$p) {
            if ($p['id'] == $projetId) {
                $p['montant_collecte'] += $montant;
                jsonResponse(['success' => true, 'message' => 'Investissement enregistré', 'data' => $p]);
            }
        }
        http_response_code(404);
        jsonResponse(['success' => false, 'message' => 'Projet non trouvé']);
        break;

    case $path === 'commandes' && $method === 'GET':
        jsonResponse(['success' => true, 'data' => $commandes]);
        break;

    case $path === 'commandes' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];
        $livraison = $input['livraison'] ?? [];
        $mode_paiement = $input['mode_paiement'] ?? 'mtn_momo';
        $sous_total = array_reduce($items, fn($sum, $item) => $sum + ($item['prix'] * $item['quantite']), 0);
        $frais_livraison = 2500;
        $montant_total = $sous_total + $frais_livraison;
        $commande = [
            'id' => count($commandes) + 1,
            'numero_commande' => 'CMD-' . time(),
            'statut' => 'en_attente',
            'sous_total' => $sous_total,
            'frais_livraison' => $frais_livraison,
            'montant_total' => $montant_total,
            'mode_paiement' => $mode_paiement,
            'statut_paiement' => 'en_attente',
            'items' => $items,
            'livraison' => $livraison,
            'date_creation' => date('c')
        ];
        $commandes[] = $commande;
        file_put_contents($commandesFile, json_encode($commandes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        jsonResponse(['success' => true, 'data' => $commande]);
        break;

    case $path === 'transport/calcul' && $method === 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $from = $input['from'] ?? '';
        $to = $input['to'] ?? '';
        $weight = $input['weight'] ?? 0;
        $distances = [
            'douala-yaounde'=>250, 'yaounde-douala'=>250,
            'douala-bafoussam'=>320, 'bafoussam-douala'=>320,
            'yaounde-bafoussam'=>180, 'bafoussam-yaounde'=>180
        ];
        $route = $from . '-' . $to;
        $distance = $distances[$route] ?? 100;
        $price = round($distance * $weight * 0.05);
        jsonResponse(['success' => true, 'data' => ['distance' => $distance, 'weight' => $weight, 'price' => $price, 'frais' => $price]]);
        break;

    default:
        http_response_code(404);
        jsonResponse(['success' => false, 'message' => 'Endpoint non trouvé', 'path' => $path]);
}

function jsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

function loadEnv($filePath) {
    $config = [];
    if (!file_exists($filePath)) {
        return $config;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $config[$key] = $value;
    }
    return $config;
}

function dataPath($path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function jwtEncode(array $payload) {
    global $jwtSecret;
    $header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload['iat'] = time();
    $payload['exp'] = time() + 3600;
    $body = base64UrlEncode(json_encode($payload));
    $signature = base64UrlEncode(hash_hmac('sha256', "$header.$body", $jwtSecret, true));
    return "$header.$body.$signature";
}

function jwtDecode(string $token) {
    global $jwtSecret;
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }
    [$header, $payload, $signature] = $parts;
    $validSignature = base64UrlEncode(hash_hmac('sha256', "$header.$payload", $jwtSecret, true));
    if (!hash_equals($validSignature, $signature)) {
        return null;
    }
    $data = json_decode(base64_decode($payload), true);
    if (!$data || (isset($data['exp']) && time() > $data['exp'])) {
        return null;
    }
    return $data;
}

function base64UrlEncode(string $data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function requireAuth($secret, $users) {
    $token = null;
    $authHeader = getAuthorizationHeader();
    if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $token = trim($matches[1]);
    }
    if (!$token && !empty($_GET['token'])) {
        $token = trim($_GET['token']);
    }
    if (!$token) {
        http_response_code(401);
        jsonResponse(['success' => false, 'message' => 'Token manquant']);
    }
    $payload = jwtDecode($token);
    if (!$payload) {
        http_response_code(401);
        jsonResponse(['success' => false, 'message' => 'Token invalide ou expiré']);
    }
    foreach ($users as $user) {
        if ($user['id'] == $payload['sub']) {
            return $user;
        }
    }
    http_response_code(401);
    jsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
}

function getAuthorizationHeader() {
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['HTTP_AUTHORIZATION']);
    }
    if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (!empty($headers['Authorization'])) {
            return trim($headers['Authorization']);
        }
        if (!empty($headers['authorization'])) {
            return trim($headers['authorization']);
        }
    }
    return null;
}

function exportCommandesCsv(array $commandes) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="agrimarket_commandes_export.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['id', 'numero_commande', 'statut', 'sous_total', 'frais_livraison', 'montant_total', 'mode_paiement', 'statut_paiement', 'date_creation']);
    foreach ($commandes as $commande) {
        fputcsv($output, [
            $commande['id'],
            $commande['numero_commande'],
            $commande['statut'],
            $commande['sous_total'],
            $commande['frais_livraison'],
            $commande['montant_total'],
            $commande['mode_paiement'],
            $commande['statut_paiement'],
            $commande['date_creation']
        ]);
    }
    fclose($output);
    exit();
}

function processmomoPayment(string $provider, string $phone, int $amount, string $externalId, array $config) {
    if ($provider === 'mtn') {
        return processMtnMoMo($phone, $amount, $externalId, $config);
    } elseif ($provider === 'orange') {
        return processOrangeMoney($phone, $amount, $externalId, $config);
    }
    return ['success' => false, 'error' => 'Provider invalide'];
}

function processMtnMoMo(string $phone, int $amount, string $externalId, array $config) {
    $apiKey = $config['MTN_MOMO_API_KEY'] ?? '';
    if (empty($apiKey)) {
        return ['success' => false, 'error' => 'Clé API MTN MoMo non configurée. Format: X-Reference-Id + API Key requis', 'data' => ['status' => 'pending', 'transaction_id' => 'DEMO-' . $externalId]];
    }
    $url = 'https://apigateway.mtn.co.tz/collection/v1_0/requesttopay';
    $payload = [
        'amount' => $amount,
        'currency' => 'XAF',
        'externalId' => $externalId,
        'payer' => ['partyIdType' => 'MSISDN', 'partyId' => $phone],
        'payerMessage' => 'Paiement AgriMarket',
        'payeeNote' => 'Livraison commande ' . $externalId
    ];
    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'X-Reference-Id: ' . $externalId,
        'Content-Type: application/json'
    ];
    $result = curlPost($url, $payload, $headers);
    if ($result && isset($result['referenceId'])) {
        return ['success' => true, 'data' => ['transaction_id' => $result['referenceId'], 'status' => 'pending', 'phone' => $phone, 'amount' => $amount]];
    }
    return ['success' => true, 'data' => ['transaction_id' => 'DEMO-' . $externalId, 'status' => 'pending', 'phone' => $phone, 'amount' => $amount, 'note' => 'Mode démo - configuration API requise']];
}

function processOrangeMoney(string $phone, int $amount, string $externalId, array $config) {
    $apiKey = $config['ORANGE_MONEY_API_KEY'] ?? '';
    if (empty($apiKey)) {
        return ['success' => false, 'error' => 'Clé API Orange Money non configurée', 'data' => ['status' => 'pending', 'transaction_id' => 'ORANGE-' . $externalId]];
    }
    $url = 'https://api.orange.com/orange-money-webpay/cm/v1/pay';
    $payload = [
        'amount' => $amount,
        'currency' => 'XAF',
        'merchant_key' => $externalId,
        'phone_number' => $phone,
        'description' => 'Achat AgriMarket'
    ];
    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ];
    $result = curlPost($url, $payload, $headers);
    if ($result && isset($result['transaction_id'])) {
        return ['success' => true, 'data' => ['transaction_id' => $result['transaction_id'], 'status' => 'pending', 'phone' => $phone, 'amount' => $amount]];
    }
    return ['success' => true, 'data' => ['transaction_id' => 'ORANGE-' . $externalId, 'status' => 'pending', 'phone' => $phone, 'amount' => $amount, 'note' => 'Mode démo - configuration API requise']];
}

function processStripePayment(int $amount, string $token, string $currency, string $description, array $config) {
    $secretKey = $config['STRIPE_SECRET_KEY'] ?? '';
    if (empty($secretKey)) {
        return ['success' => false, 'error' => 'Clé secrète Stripe non configurée', 'data' => ['status' => 'pending', 'transaction_id' => 'DEMO-' . time()]];
    }
    $url = 'https://api.stripe.com/v1/charges';
    $payload = [
        'amount' => $amount * 100,
        'currency' => strtolower($currency),
        'source' => $token,
        'description' => $description
    ];
    $headers = ['Authorization: Bearer ' . $secretKey];
    $result = curlPost($url, $payload, $headers);
    if ($result && isset($result['id'])) {
        return ['success' => true, 'data' => ['transaction_id' => $result['id'], 'status' => $result['status'], 'amount' => $amount, 'currency' => $currency]];
    }
    return ['success' => true, 'data' => ['transaction_id' => 'DEMO-' . time(), 'status' => 'pending', 'amount' => $amount, 'currency' => $currency, 'note' => 'Mode démo - configuration API requise']];
}

function sendEmailNotification(string $to, string $subject, string $body, array $config) {
    $sendgridKey = $config['SENDGRID_API_KEY'] ?? '';
    if (!empty($sendgridKey)) {
        return sendViaMailgun($to, $subject, $body, $config);
    }
    if (!empty($config['MAILGUN_DOMAIN'])) {
        return sendViaMailgun($to, $subject, $body, $config);
    }
    return ['success' => true, 'message' => 'Email prêt à être envoyé (mode démo)', 'to' => $to];
}

function sendViaMailgun(string $to, string $subject, string $body, array $config) {
    $domain = $config['MAILGUN_DOMAIN'] ?? '';
    $apiKey = $config['MAILGUN_API_KEY'] ?? '';
    if (empty($domain) || empty($apiKey)) {
        return ['success' => true, 'message' => 'Email simulé (clés Mailgun manquantes)', 'to' => $to];
    }
    $url = "https://api.mailgun.net/v3/$domain/messages";
    $payload = [
        'from' => $config['MAILGUN_FROM'] ?? 'noreply@agrimarket.cm',
        'to' => $to,
        'subject' => $subject,
        'html' => $body
    ];
    $result = curlPost($url, $payload, [], base64_encode("api:$apiKey"));
    if ($result && isset($result['id'])) {
        return ['success' => true, 'message' => 'Email envoyé avec succès', 'to' => $to];
    }
    return ['success' => true, 'message' => 'Email prêt à être envoyé', 'to' => $to];
}

function sendSmsNotification(string $to, string $text, array $config) {
    $twilioSid = $config['TWILIO_SID'] ?? '';
    $twilioToken = $config['TWILIO_TOKEN'] ?? '';
    if (!empty($twilioSid) && !empty($twilioToken)) {
        return sendViaTwilio($to, $text, $config);
    }
    $africasTalkingKey = $config['AFRICAS_TALKING_KEY'] ?? '';
    if (!empty($africasTalkingKey)) {
        return sendViaAfricasTalking($to, $text, $config);
    }
    return ['success' => true, 'message' => 'SMS prêt à être envoyé (mode démo)', 'to' => $to];
}

function sendViaTwilio(string $to, string $text, array $config) {
    $sid = $config['TWILIO_SID'] ?? '';
    $token = $config['TWILIO_TOKEN'] ?? '';
    $from = $config['TWILIO_FROM'] ?? '';
    if (empty($from)) {
        return ['success' => true, 'message' => 'SMS simulé (clés Twilio incomplètes)', 'to' => $to];
    }
    $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
    $payload = ['From' => $from, 'To' => $to, 'Body' => $text];
    $auth = base64_encode("$sid:$token");
    $result = curlPost($url, $payload, [], $auth);
    if ($result && isset($result['sid'])) {
        return ['success' => true, 'message' => 'SMS envoyé avec succès', 'to' => $to];
    }
    return ['success' => true, 'message' => 'SMS prêt à être envoyé', 'to' => $to];
}

function sendViaAfricasTalking(string $to, string $text, array $config) {
    $apiKey = $config['AFRICAS_TALKING_KEY'] ?? '';
    $username = $config['AFRICAS_TALKING_USERNAME'] ?? '';
    if (empty($username)) {
        return ['success' => true, 'message' => 'SMS simulé (configuration AfricasTalking incomplète)', 'to' => $to];
    }
    $url = 'https://api.sandbox.africastalking.com/version1/messaging';
    $payload = ['username' => $username, 'recipients' => $to, 'message' => $text];
    $headers = ['Accept: application/json', "apiKey: $apiKey"];
    $result = curlPost($url, $payload, $headers);
    if ($result && isset($result['SMSMessageData'])) {
        return ['success' => true, 'message' => 'SMS envoyé avec succès', 'to' => $to];
    }
    return ['success' => true, 'message' => 'SMS prêt à être envoyé', 'to' => $to];
}

function curlPost(string $url, array $payload, array $headers = [], string $auth = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if (!empty($auth)) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode >= 200 && $httpCode < 300) {
        return json_decode($response, true);
    }
    return null;
}

function testDatabaseConnection(array $config) {
    if (empty($config['DB_HOST']) || empty($config['DB_NAME']) || empty($config['DB_USER'])) {
        return ['connected' => false, 'message' => 'Configuration de base de données manquante'];
    }
    try {
        $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['DB_HOST'], $config['DB_NAME']), $config['DB_USER'], $config['DB_PASSWORD'] ?? '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ['connected' => true, 'database' => $config['DB_NAME']];
    } catch (PDOException $ex) {
        return ['connected' => false, 'message' => $ex->getMessage()];
    }
}
