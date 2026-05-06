# API AGRILINK V1 - Documentation

**Base de données** : `agrilink_v1`  
**Schéma** : Structure optimisée avec vendeurs, financement, logistique

## 📝 Endpoints disponibles

### Produits
```
GET /agrimarket/api_v1/index.php?endpoint=produits
```
Retourne la liste de tous les produits avec catégorie et vendeur

### Catégories
```
GET /agrimarket/api_v1/index.php?endpoint=categories
```
Retourne toutes les catégories de produits

### Vendeurs
```
GET /agrimarket/api_v1/index.php?endpoint=vendeurs
```
Retourne tous les vendeurs triés par note (rating)

### Commandes
```
GET /agrimarket/api_v1/index.php?endpoint=commandes
```
Retourne toutes les commandes avec détails produit et vendeur

### Statistiques
```
GET /agrimarket/api_v1/index.php?endpoint=stats
```
Retourne les statistiques globales (produits, vendeurs, commandes, régions)

## 📊 Données actuelles (agrilink_v1)

- **8 Produits** : Tomates, Maïs, Poulets, Plantains, Cacao, Manioc, Piment, Oeufs
- **8 Vendeurs** : Avec notes et régions
- **7 Régions** : Centre, Ouest, Littoral, Est, Sud, Nord, Extrême-Nord
- **6 Catégories** : Fruits-Légumes, Céréales, Viande, Produits Laitiers, Phyto, Aviculture
- **5 Commandes** : Enregistrées avec statuts variés

## 🔧 Utilisation

### Test simple dans le navigateur
```
http://localhost/agrimarket/api_v1/index.php?endpoint=stats
```

### Exemple de réponse
```json
{
  "success": true,
  "data": {
    "produits": 8,
    "vendeurs": 8,
    "commandes": 5,
    "regions": 7
  }
}
```

## 📂 Fichiers

- `index.php` - API principale
- `.htaccess` - Configuration des URLs
- `test.php` - Fichier de test simple

## 🚀 Prochaines étapes

1. Activer mod_rewrite pour URLs propres
2. Ajouter authentification
3. Implémenter POST pour créer commandes
4. Ajouter pagination pour les résultats

## 💡 Accès phpMyAdmin

```
http://localhost/phpmyadmin
```

Sélectionner la base `agrilink_v1` pour voir/modifier les données