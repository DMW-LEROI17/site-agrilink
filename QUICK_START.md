# 🚀 Démarrage Rapide - Netlify Deployment

## 5 Minutes pour Déployer AgriMarket sur Netlify

### Étape 1 : Préparation (2 minutes)

#### Sur votre ordinateur :
```bash
# Clone ou préparez votre dossier
cd c:\Users\hp probook\Desktop\NEW PROJET

# Si pas de Git, initialisez
git init
git add .
git commit -m "Initial AgriMarket deployment"
```

### Étape 2 : Créer un compte Netlify (2 minutes)

1. Allez sur https://app.netlify.com/
2. **Sign up** avec GitHub / Google
3. Une fois connecté, cliquez sur **"New site from Git"**

### Étape 3 : Déployer (1 minute)

#### Option A : Drag & Drop (plus rapide) 🎯
1. Allez sur https://app.netlify.com/drop
2. Glissez-déposez votre dossier `c:\Users\hp probook\Desktop\NEW PROJET`
3. **Voilà !** Votre site est en ligne à `https://your-site-123.netlify.app`

#### Option B : GitHub
1. Connectez Netlify à votre repo GitHub
2. Sélectionnez le repo et la branche `main`
3. Build command : `echo 'Ready'`
4. Publish directory : `.`
5. Deploy !

### Étape 4 : Configurer les APIs (5 minutes après)

Sur le dashboard Netlify :

1. **Settings → Environment variables**
2. Ajouter :
   ```
   AGRIMARKET_API=https://votre-api.vercel.app
   STRIPE_PUBLIC_KEY=pk_live_xxxxx
   ```

### ✅ C'est fait ! Votre site est en ligne

Accédez à : `https://votre-site.netlify.app`

---

## 🔧 Configuration du Backend (optionnel mais recommandé)

### Déployer le backend PHP sur Vercel

1. Allez sur https://vercel.com/
2. Import → Git → Sélectionnez votre repo
3. Framework Preset : **Other**
4. Build : `echo 'API ready'`
5. Output : `.`
6. Deploy !

### Mettre à jour `.env` après déploiement

```
AGRIMARKET_API=https://votre-api.vercel.app/backend
```

---

## 📊 Vérifier votre déploiement

### Tester le site
```bash
# Votre site est à :
https://your-site.netlify.app

# Vérifier la console (F12)
# Pas d'erreurs CORS ? ✅ Parfait !
```

### Tester l'API (si backend déployé)
```bash
curl https://your-api.vercel.app/backend/index.php?path=health
# Réponse attendue : {"status":"ok"}
```

---

## 🔐 Points importants

1. **HTTPS** : Gratuit sur Netlify ✅
2. **Domaine custom** : Settings → Domain management
3. **Variables d'environnement** : Ne JAMAIS committer les clés API
4. **Logs** : Netlify → Deploys → Voir les détails

---

## 📈 Prochaines étapes

- [ ] Déployer le backend (Vercel/Heroku/Railway)
- [ ] Configurer les APIs (MTN, Orange, Stripe, etc.)
- [ ] Ajouter un domaine custom
- [ ] Mettre en place la base de données cloud
- [ ] Créer l'application mobile

---

## ❌ Problèmes courants

### Erreur CORS ?
→ Vérifier la configuration de redirection dans `netlify.toml`

### API répond 404 ?
→ Vérifier que le backend est déployé et `AGRIMARKET_API` est correct

### Changements ne s'affichent pas ?
→ Forcer le rafraîchissement : `Ctrl+Shift+Delete` (hard refresh)

---

**Besoin d'aide ?** Consultez [NETLIFY_DEPLOYMENT.md](NETLIFY_DEPLOYMENT.md) pour le guide complet.

---

**Status** : ✅ Production Ready  
**Version** : 1.0  
**Date** : Mai 2026
