# 🚀 Déploiement Netlify - Guide Étape par Étape

## Prérequis
- Un compte GitHub (gratuit)
- Un compte Netlify (gratuit)

---

## Étape 1 : Préparer le Repository GitHub

### 1.1 Créer un repository sur GitHub
1. Allez sur https://github.com/new
2. Nom : `agrimarket-netlify`
3. Description : `Marketplace agricole AgriMarket Cameroun`
4. Public ou Private (selon vos préférences)
5. **Ne pas** cocher "Add a README file"
6. Cliquez "Create repository"

### 1.2 Initialiser Git localement
```bash
# Dans votre dossier projet
cd "c:\Users\hp probook\Desktop\NEW PROJET"

# Initialiser Git
git init

# Ajouter tous les fichiers
git add .

# Premier commit
git commit -m "Initial commit - AgriMarket Cameroun"

# Ajouter le remote GitHub
git remote add origin https://github.com/VOTRE_USERNAME/agrimarket-netlify.git

# Pousser vers GitHub
git push -u origin main
```

---

## Étape 2 : Déployer sur Netlify

### 2.1 Se connecter à Netlify
1. Allez sur https://app.netlify.com/
2. Connectez-vous avec GitHub

### 2.2 Créer un nouveau site
1. Cliquez "New site from Git"
2. Choisissez "GitHub"
3. Autorisez Netlify à accéder à vos repos
4. Sélectionnez `agrimarket-netlify`
5. Branche : `main`

### 2.3 Configurer le build
- **Build command** : `echo 'Static site ready'`
- **Publish directory** : `.`
- Cliquez "Deploy site"

### 2.4 Attendre le déploiement
- Netlify va construire et déployer votre site
- Vous obtiendrez une URL : `https://random-name.netlify.app`

---

## Étape 3 : Configurer les Variables d'Environnement

### 3.1 Dans Netlify Dashboard
1. Allez dans votre site → "Settings" → "Environment variables"
2. Ajoutez :
   ```
   AGRIMARKET_API = https://api.agrimarket.cm/backend/index.php?path=
   ```

### 3.2 Redeployer
1. Cliquez "Deploy site" → "Trigger deploy" → "Deploy site"
2. Attendez que ce soit terminé

---

## Étape 4 : Vérifier le Déploiement

### 4.1 Tester le frontend
- Ouvrez `https://votre-site.netlify.app`
- La page devrait charger correctement

### 4.2 Tester les APIs
- Santé : `https://votre-site.netlify.app/api/health`
- Auth : `https://votre-site.netlify.app/api/auth/login`

### 4.3 Vérifier les redirections
- Les appels `/api/*` devraient être redirigés vers votre backend

---

## Étape 5 : Personnaliser (Optionnel)

### 5.1 Domaine personnalisé
1. Settings → Domain management
2. Add custom domain
3. Configurez votre DNS

### 5.2 Nom du site
1. Settings → Site details
2. Change site name

---

## Dépannage

### Erreur de build
- Vérifiez que `netlify.toml` est présent
- Build command doit être `echo 'Static site ready'`

### APIs ne fonctionnent pas
- Vérifiez `AGRIMARKET_API` dans les variables d'environnement
- Assurez-vous que le backend est déployé et accessible

### Redirections ne marchent pas
- Vérifiez `_redirects` et `netlify.toml`

---

## Fichiers importants pour Netlify

- `netlify.toml` : Configuration du build et redirections
- `_redirects` : Règles de redirection API
- `AGRI-LINK.html` : Votre application principale
- `site-agrilink.html` : Site marketing

---

## Prochaines étapes après déploiement

1. ✅ Déployer le backend PHP sur un serveur (Vercel/Heroku)
2. ✅ Configurer les vraies APIs (MTN, Stripe, etc.)
3. ✅ Tester les paiements en production
4. ✅ Ajouter un domaine personnalisé

---

**Temps estimé** : 15-30 minutes
**Coût** : Gratuit (Netlify offre un plan gratuit généreux)

---

*Guide créé le 06 Mai 2026*
