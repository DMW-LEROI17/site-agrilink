// =====================================================
// AGRIMARKET CAMEROUN - Serveur API Node.js/Express
// =====================================================

const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// =====================================================
// DONNÉES EN MÉMOIRE (Simulation base de données)
// =====================================================

let users = [
    { id: 1, nom: 'Kouam', prenom: 'Jean', email: 'jean@email.com', telephone: '+237612345678', role: 'vendeur', statut: 'actif', note_moyenne: 4.5 },
    { id: 2, nom: 'Tagne', prenom: 'Marie', email: 'marie@email.com', telephone: '+237612345679', role: 'acheteur', statut: 'actif', note_moyenne: 4.8 }
];

let categories = [
    { id: 1, nom: 'Légumes', icone: 'fa-leaf' },
    { id: 2, nom: 'Fruits', icone: 'fa-apple-alt' },
    { id: 3, nom: 'Céréales', icone: 'fa-seedling' },
    { id: 4, nom: 'Tubercules', icone: 'fa-carrot' }
];

let produits = [
    { id: 1, vendor_id: 1, categorie_id: 3, nom: 'Maïs blanc', description: 'Maïs blanc de qualité supérieure', prix_unitaire: 3500, unite: 'kg', quantite_disponible: 200, region: 'Bafoussam', statut_stock: 'en_stock', note_moyenne: 4.5, nb_ventes: 45 },
    { id: 2, vendor_id: 1, categorie_id: 1, nom: 'Tomates fraîches', description: 'Tomates fraîches de Foumbot', prix_unitaire: 1200, unite: 'bassin', quantite_disponible: 50, region: 'Foumbot', statut_stock: 'en_stock', note_moyenne: 4.7, nb_ventes: 32 },
    { id: 3, vendor_id: 1, categorie_id: 3, nom: 'Arachides', description: 'Arachides de Garoua', prix_unitaire: 2800, unite: 'kg', quantite_disponible: 150, region: 'Garoua', statut_stock: 'en_stock', note_moyenne: 4.2, nb_ventes: 28 },
    { id: 4, vendor_id: 1, categorie_id: 4, nom: 'Manioc frais', description: 'Manioc frais de Yaoundé', prix_unitaire: 800, unite: 'kg', quantite_disponible: 300, region: 'Yaoundé', statut_stock: 'en_stock', note_moyenne: 4.6, nb_ventes: 67 },
    { id: 5, vendor_id: 1, categorie_id: 2, nom: 'Bananes plantains', description: 'Bananes plantains de Douala', prix_unitaire: 500, unite: 'kg', quantite_disponible: 100, region: 'Douala', statut_stock: 'en_stock', note_moyenne: 4.4, nb_ventes: 23 },
    { id: 6, vendor_id: 1, categorie_id: 1, nom: 'Feuille de manioc', description: 'Feuilles de manioc fraîches', prix_unitaire: 2500, unite: 'fagot', quantite_disponible: 50, region: 'Bafoussam', statut_stock: 'stock_limite', note_moyenne: 4.3, nb_ventes: 15 }
];

let produitsPhyto = [
    { id: 1, vendor_id: 1, categorie: 'engrais', nom: 'Engrais NPK 20-10-10', description: 'Engrais NPK pour cultures', prix: 18500, unite: 'sac 50kg', marque: 'AgriChem', promo_pourcentage: 0 },
    { id: 2, vendor_id: 1, categorie: 'semences', nom: 'Semences maize hybride', description: 'Semences à haut rendement', prix: 4200, unite: 'kg', marque: 'Semences Tropicales', promo_pourcentage: 10 },
    { id: 3, vendor_id: 1, categorie: 'pesticides', nom: 'Insecticide total', description: 'Contre pucerons et chenilles', prix: 8500, unite: 'L', marque: 'PhytoCam', promo_pourcentage: 0 },
    { id: 4, vendor_id: 1, categorie: 'engrais', nom: 'BioFert Organique', description: 'Compost biologique', prix: 12000, unite: 'sac 25kg', marque: 'BioFert', promo_pourcentage: 0 },
    { id: 5, vendor_id: 1, categorie: 'equipement', nom: 'Kit irrigation goutte-à-goutte', description: 'Pour 500m²', prix: 145000, unite: 'kit', marque: 'IrrigTech', promo_pourcentage: 0 }
];

let commandes = [];
let paniers = [];
let projets = [
    { id: 1, createur_id: 1, titre: 'Élevage poulets de chair', description: 'Élevage de 500 poulets', type_projet: 'elevage', budget_total: 2500000, montant_collecte: 1625000, taux_retour: 22, duree_mois: 12, localisation: 'Yaoundé', statut: 'en_cours' },
    { id: 2, createur_id: 1, titre: 'Transformation manioc en farine', description: 'Usine de transformation', type_projet: 'transformation', budget_total: 5000000, montant_collecte: 2000000, taux_retour: 18, duree_mois: 18, localisation: 'Bafoussam', statut: 'en_cours' },
    { id: 3, createur_id: 1, titre: 'Pisciculture tilapia', description: 'Élevage de tilapia', type_projet: 'pisciculture', budget_total: 1800000, montant_collecte: 1530000, taux_retour: 28, duree_mois: 8, localisation: 'Kribi', statut: 'en_cours' }
];

let livraisons = [
    { id: 1, commande_id: null, transporteur_id: 1, numero_suivi: 'LIV-4521', statut: 'en_transit', ville_depart: 'Yaoundé', ville_arrivee: 'Douala', distance_km: 250, frais: 2500, date_livraison_prevue: new Date() }
];

let notifications = [
    { id: 1, user_id: 1, type: 'commande', titre: 'Commande confirmée', message: 'Votre commande #CMD-1745 a été confirmée', lu: false },
    { id: 2, user_id: 1, type: 'livraison', titre: 'Livraison en cours', message: 'Votre commande est en route vers Douala', lu: false },
    { id: 3, user_id: 1, type: 'paiement', titre: 'Nouveau paiement reçu', message: 'Vous avez reçu 18 500 FCAF', lu: true }
];

let commandeIdCounter = 1;

// =====================================================
// ROUTES API - PRODUITS
// =====================================================

app.get('/api/produits', (req, res) => {
    res.json({ success: true, data: produits });
});

app.get('/api/produits/:id', (req, res) => {
    const produit = produits.find(p => p.id === parseInt(req.params.id));
    if (produit) {
        res.json({ success: true, data: produit });
    } else {
        res.status(404).json({ success: false, message: 'Produit non trouvé' });
    }
});

app.post('/api/produits', (req, res) => {
    const { vendor_id, categorie_id, nom, description, prix_unitaire, unite, quantite_disponible, region } = req.body;
    const newProduit = {
        id: produits.length + 1,
        vendor_id: vendor_id || 1,
        categorie_id: categorie_id || 1,
        nom,
        description: description || '',
        prix_unitaire: parseInt(prix_unitaire),
        unite: unite || 'kg',
        quantite_disponible: parseInt(quantite_disponible) || 0,
        region: region || '',
        statut_stock: 'en_stock',
        note_moyenne: 0,
        nb_ventes: 0
    };
    produits.push(newProduit);
    res.json({ success: true, data: newProduit });
});

// =====================================================
// ROUTES API - CATÉGORIES
// =====================================================

app.get('/api/categories', (req, res) => {
    res.json({ success: true, data: categories });
});

// =====================================================
// ROUTES API - PRODUITS PHYTO
// =====================================================

app.get('/api/phyto', (req, res) => {
    res.json({ success: true, data: produitsPhyto });
});

// =====================================================
// ROUTES API - COMMANDES
// =====================================================

app.get('/api/commandes', (req, res) => {
    res.json({ success: true, data: commandes });
});

app.get('/api/commandes/user/:userId', (req, res) => {
    const userCommandes = commandes.filter(c => c.client_id === parseInt(req.params.userId));
    res.json({ success: true, data: userCommandes });
});

app.post('/api/commandes', (req, res) => {
    const { client_id, items, livraison, mode_paiement } = req.body;
    
    const sous_total = items.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    const frais_livraison = 2500;
    const montant_total = sous_total + frais_livraison;
    
    const newCommande = {
        id: commandeIdCounter++,
        client_id: client_id || 1,
        numero_commande: 'CMD-' + Date.now(),
        statut: 'en_attente',
        sous_total,
        frais_livraison,
        montant_total,
        mode_paiement: mode_paiement || 'mtn_momo',
        statut_paiement: 'en_attente',
        items,
        livraison,
        date_creation: new Date()
    };
    
    commandes.push(newCommande);
    
    notifications.unshift({
        id: notifications.length + 1,
        user_id: client_id || 1,
        type: 'commande',
        titre: 'Nouvelle commande',
        message: 'Commande ' + newCommande.numero_commande + ' créée avec succès',
        lu: false
    });
    
    res.json({ success: true, data: newCommande });
});

// =====================================================
// ROUTES API - PANIER
// =====================================================

app.get('/api/panier/:userId', (req, res) => {
    const userPanier = paniers.filter(p => p.user_id === parseInt(req.params.userId));
    res.json({ success: true, data: userPanier });
});

app.post('/api/panier', (req, res) => {
    const { user_id, produit_id, quantite } = req.body;
    
    const existingItem = paniers.find(p => p.user_id === user_id && p.produit_id === produit_id);
    
    if (existingItem) {
        existingItem.quantite += quantite || 1;
    } else {
        paniers.push({
            id: paniers.length + 1,
            user_id: user_id || 1,
            produit_id: produit_id,
            quantite: quantite || 1
        });
    }
    
    res.json({ success: true, message: 'Produit ajouté au panier' });
});

app.delete('/api/panier/:userId/:produitId', (req, res) => {
    paniers = paniers.filter(p => !(p.user_id === parseInt(req.params.userId) && p.produit_id === parseInt(req.params.produitId)));
    res.json({ success: true, message: 'Produit retiré du panier' });
});

// =====================================================
// ROUTES API - PROJETS
// =====================================================

app.get('/api/projets', (req, res) => {
    res.json({ success: true, data: projets });
});

app.post('/api/projets/investir', (req, res) => {
    const { projet_id, investisseur_id, montant } = req.body;
    
    const projet = projets.find(p => p.id === projet_id);
    if (projet) {
        projet.montant_collecte += parseInt(montant);
        res.json({ success: true, message: 'Investissement enregistré', data: projet });
    } else {
        res.status(404).json({ success: false, message: 'Projet non trouvé' });
    }
});

// =====================================================
// ROUTES API - LIVRAISONS
// =====================================================

app.get('/api/livraisons', (req, res) => {
    res.json({ success: true, data: livraisons });
});

app.get('/api/livraisons/:numero', (req, res) => {
    const livraison = livraisons.find(l => l.numero_suivi === req.params.numero);
    if (livraison) {
        res.json({ success: true, data: livraison });
    } else {
        res.status(404).json({ success: false, message: 'Livraison non trouvée' });
    }
});

// =====================================================
// ROUTES API - NOTIFICATIONS
// =====================================================

app.get('/api/notifications/:userId', (req, res) => {
    const userNotifs = notifications.filter(n => n.user_id === parseInt(req.params.userId));
    res.json({ success: true, data: userNotifs });
});

app.put('/api/notifications/:userId/read', (req, res) => {
    notifications.forEach(n => {
        if (n.user_id === parseInt(req.params.userId)) {
            n.lu = true;
        }
    });
    res.json({ success: true, message: 'Notifications marquées comme lues' });
});

// =====================================================
// ROUTES API - TRANSPORT
// =====================================================

app.post('/api/transport/calcul', (req, res) => {
    const { from, to, weight } = req.body;
    
    const distances = {
        'douala-yaounde': 250, 'yaounde-douala': 250,
        'douala-bafoussam': 320, 'bafoussam-douala': 320,
        'yaounde-bafoussam': 180, 'bafoussam-yaounde': 180
    };
    
    const route = from + '-' + to;
    const distance = distances[route] || 100;
    const price = Math.round(distance * weight * 0.05);
    
    res.json({ 
        success: true, 
        data: { 
            distance, 
            weight, 
            price,
            frais: price
        } 
    });
});

// =====================================================
// ROUTES API - UTILISATEURS
// =====================================================

app.get('/api/users', (req, res) => {
    res.json({ success: true, data: users });
});

app.get('/api/users/:id', (req, res) => {
    const user = users.find(u => u.id === parseInt(req.params.id));
    if (user) {
        res.json({ success: true, data: user });
    } else {
        res.status(404).json({ success: false, message: 'Utilisateur non trouvé' });
    }
});

// =====================================================
// ROUTE DE SANTÉ
// =====================================================

app.get('/api/health', (req, res) => {
    res.json({ 
        success: true, 
        message: 'API AgriMarket Cameroun',
        version: '1.0.0',
        timestamp: new Date()
    });
});

// =====================================================
// DÉMARRAGE DU SERVEUR
// =====================================================

app.listen(PORT, () => {
    console.log(`
╔═══════════════════════════════════════════════════╗
║                                                   ║
║   🌾 AGRIMARKET CAMEROUN - API Server             ║
║   =========================================         ║
║                                                   ║
║   Serveur démarré sur le port: ${PORT}              ║
║   Mode: ${process.env.NODE_ENV || 'development'}                            ║
║                                                   ║
║   Endpoints disponibles:                           ║
║   - GET    /api/health                             ║
║   - GET    /api/produits                           ║
║   - GET    /api/categories                         ║
║   - GET    /api/phyto                              ║
║   - POST   /api/commandes                          ║
║   - GET    /api/commandes/user/:id                 ║
║   - POST   /api/panier                             ║
║   - GET    /api/projets                            ║
║   - POST   /api/projets/investir                   ║
║   - GET    /api/livraisons                         ║
║   - GET    /api/notifications/:id                  ║
║   - POST   /api/transport/calcul                   ║
║                                                   ║
╚═══════════════════════════════════════════════════╝
    `);
});

module.exports = app;