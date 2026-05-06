# 🌾 AgriMarket Cameroun - AGRILINK V1

## Application Web de Marketplace Agricole

### ✅ État du projet : **FONCTIONNEL ET PRÊT POUR INTÉGRATION RÉELLE**

L'application est opérationnelle avec :
- ✅ Serveur WAMP64 local (Apache 2.4.62, PHP 8.3.14, MySQL 9.1.0)
- ✅ Backend PHP avec JWT auth et endpoints /auth, /admin, /payments, /notifications
- ✅ Frontend `AGRI-LINK.html` prêt à utiliser l'API
- ✅ Site marketing `site-agrilink.html`
- ✅ Application mobile Android APK générée
- ✅ Documentation et scripts de déploiement Netlify

---

## 📁 Structure principale du projet

```
C:\wamp64\www\agrimarket\
├── AGRI-LINK.html              ← Application marketplace
├── site-agrilink.html          ← Site marketing statique
├── TEST_APIS.html              ← Interface de test API
├── backend\                   ← Backend PHP principal
│   ├── index.php               ← API REST principale
│   ├── .env                    ← Configuration API locales/réelles
│   ├── .env.example            ← Modèle de configuration
│   └── .htaccess               ← Rewrites et contrôles d'autorisation
├── DEPLOY_NETLIFY_GUIDE.md     ← Guide de déploiement Netlify
├── deploy-netlify.bat          ← Script de déploiement automatique
├── QUICK_START.md             ← Démarrage rapide
├── DEPLOYMENT_CHECKLIST.md     ← Checklist d'intégration
└── setup-apis.sh               ← Script d'assistance à la configuration
```

---

## 🌐 Accès local

### Frontend
- `http://localhost/agrimarket/AGRI-LINK.html`
- `http://localhost/agrimarket/site-agrilink.html`
- `TEST_APIS.html` ouvre une page de test interactif localement

### Backend
- `http://localhost/agrimarket/backend/index.php?path=health`
- `http://localhost/agrimarket/backend/index.php?path=db/test`
- `http://localhost/agrimarket/backend/index.php?path=auth/login`
- `http://localhost/agrimarket/backend/index.php?path=admin/dashboard`

### Base de données
- `http://localhost/phpmyadmin/?db=agrilink_v1`

### Comment tester en local
1. Démarrez WAMP et assurez-vous qu’Apache et MySQL sont activés.
2. Ouvrez `AGRI-LINK.html` dans un navigateur.
3. Vérifiez que le backend répond avec `http://localhost/agrimarket/backend/index.php?path=health`.
4. Utilisez `TEST_APIS.html` pour tester les paiements, emails et SMS.

## 🚀 Déploiement Netlify

### Déploiement rapide (5 minutes)
1. Double-cliquez sur `deploy-netlify.bat`
2. Suivez les instructions pour GitHub
3. Allez sur https://app.netlify.com/ et déployez depuis Git

### Guide complet
Voir `DEPLOY_NETLIFY_GUIDE.md` pour les étapes détaillées.

---

## 🔧 Technologies utilisées

- **Frontend** : HTML5, CSS3, JavaScript
- **Backend** : PHP 8.3, JWT, MySQL
- **Base de données** : MySQL 9.1
- **Serveur** : Apache 2.4 (WAMP64)
- **Mobile** : Capacitor, Android SDK 33
- **Build** : Gradle, JDK 17

---

## 🔌 API Back-end disponibles

### Endpoints principaux

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/backend/index.php?path=health` | Vérifie l'état de l'API |
| GET | `/backend/index.php?path=db/test` | Teste la connexion MySQL |
| POST | `/backend/index.php?path=auth/login` | Connexion utilisateur |
| POST | `/backend/index.php?path=auth/register` | Inscription utilisateur |
| GET | `/backend/index.php?path=auth/profile` | Profil JWT |
| GET | `/backend/index.php?path=admin/dashboard` | Tableau de bord admin |
| GET | `/backend/index.php?path=admin/export/commandes` | Export CSV commandes |
| POST | `/backend/index.php?path=payments/momo` | Paiement Mobile Money (MTN/Orange) |
| POST | `/backend/index.php?path=payments/stripe` | Paiement carte Stripe |
| POST | `/backend/index.php?path=notifications/email` | Envoi email |
| POST | `/backend/index.php?path=notifications/sms` | Envoi SMS |

---

## 📌 Configuration .env

Le backend utilise le fichier `backend/.env` pour stocker les clés et la configuration.

### Exemple de valeurs à renseigner

```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=agrilink_v1
JWT_SECRET=agrimarket_secret_key_2024

# Mobile Money
MTN_MOMO_API_KEY=your_mtn_momo_api_key_here
MTN_MOMO_SUBSCRIPTION_KEY=your_mtn_subscription_key_here
ORANGE_MONEY_API_KEY=your_orange_money_api_key_here

# Stripe
STRIPE_PUBLIC_KEY=pk_test_your_stripe_public_key_here
STRIPE_SECRET_KEY=sk_test_your_stripe_secret_key_here

# Email
SENDGRID_API_KEY=your_sendgrid_api_key_here
MAILGUN_DOMAIN=your_domain.mailgun.org
MAILGUN_API_KEY=key-your_mailgun_api_key_here
MAILGUN_FROM=noreply@agrimarket.cm

# SMS
TWILIO_SID=your_twilio_sid_here
TWILIO_TOKEN=your_twilio_token_here
TWILIO_FROM=+1234567890
AFRICAS_TALKING_KEY=your_africas_talking_api_key_here
AFRICAS_TALKING_USERNAME=your_africas_talking_username_here
```

### Frontend local
- Ouvrez `AGRI-LINK.html`.
- Vérifiez que la variable `API_BASE` est définie comme suit :
  ```js
  const API_BASE = 'http://localhost/agrimarket/backend/index.php?path=';
  ```
- Cela permet au frontend de pointer vers le backend PHP local.

### Fichiers de configuration importants
- `backend/.env` : configuration locale et production
- `backend/.env.example` : modèle de configuration
- `backend/.htaccess` : réécriture et header Authorization
- `netlify.toml` : configuration Netlify
- `_redirects` : redirections API Netlify

---

## 🚀 Déploiement Netlify

### Frontend
- Déployer `AGRI-LINK.html`, `site-agrilink.html` et les assets statiques sur Netlify
- Build command : `echo 'Ready'`
- Publish directory : `.`

### Backend
- Le backend PHP doit être déployé sur un hébergement externe (Vercel, Heroku, Railway, VPS)
- Mettre à jour `AGRIMARKET_API` vers l'URL distante dans Netlify
- Exemple : `AGRIMARKET_API=https://api.agrimarket.cm/backend/index.php?path=`

### Redirections API Netlify
- Utiliser `_redirects` et `netlify.toml` pour rediriger `/api/*` vers le backend distant
- Exemple de règle : `/api/* https://api.agrimarket.cm/backend/index.php?path=:splat 200!`


---

## 🧪 Outils de test

- `TEST_APIS.html` : interface de test pour Mobile Money, Stripe, email, SMS
- `NETLIFY_DEPLOYMENT.md` : guide complet de déploiement
- `QUICK_START.md` : installation rapide
- `DEPLOYMENT_CHECKLIST.md` : vérification des éléments clés

---

## 🎯 Avancement des fonctionnalités

### Ce qui est déjà implémenté
- ✅ Marketplace responsive
- ✅ Panier et checkout
- ✅ JWT Auth
- ✅ Dashboard admin
- ✅ Export CSV commandes
- ✅ Paiement Mobile Money (MTN/Orange)
- ✅ Paiement Stripe
- ✅ Notifications email et SMS
- ✅ Backend PHP prêt pour production
- ✅ Site statique pour Netlify

### Ce qui reste à activer
- ⚠️ Clés API réelles à renseigner dans `backend/.env`
- ⚠️ Backend distant à déployer pour Netlify
- ⚠️ Passage des paiements live en production (Stripe, MTN, Orange)

---

## 📱 Application Android

- **APK Debug** : `C:\wamp64\www\agrimarket\android\app\build\outputs\apk\debug\app-debug.apk`
- **Taille** : ~3.7 MB
- **Plateforme** : Android API 33

### Installation
1. Transférer l'APK sur un appareil Android
2. Autoriser l'installation d'applications inconnues
3. Installer le fichier APK

---

## 🔧 Bonnes pratiques

1. Ne pas committer `backend/.env` avec des clés réelles
2. Utiliser HTTPS pour les appels API en production
3. Vérifier les logs Apache et Netlify après chaque déploiement
4. Tester les endpoints via `TEST_APIS.html`

---

## 📞 Support

- Email : support@agrimarket.cm
- Téléphone : +237 6XX XXX XXX

---

**Créé le :** 06 Mai 2026
**Version :** 1.0.1
