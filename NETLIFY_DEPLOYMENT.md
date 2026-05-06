# 📱 Déploiement Netlify - AgriMarket Cameroun

Guide complet pour déployer votre application AgriMarket sur Netlify avec intégration des vraies APIs.

## 🚀 Déploiement sur Netlify

### Étape 1 : Préparation des fichiers

1. **Créer un repository GitHub** :
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/votre-username/agrimarket.git
   git push -u origin main
   ```

2. **Structure Netlify** :
   ```
   ├── index.html          (votre application principale)
   ├── netlify.toml        (configuration Netlify)
   ├── _redirects          (règles de redirection)
   ├── backend/            (API PHP - déployer ailleurs)
   └── api/                (Netlify Functions)
   ```

### Étape 2 : Déployer sur Netlify

**Option 1 : Drag & Drop (rapide)**
1. Allez sur https://app.netlify.com/drop
2. Glissez-déposez votre fichier `index.html`
3. Votre site est en ligne ! URL : `https://votre-nom.netlify.app`

**Option 2 : GitHub (recommandé)**
1. Connectez votre repository GitHub à Netlify
2. Settings → Deploy → Create deploy
3. Netlify crée une URL : `https://votre-repo.netlify.app`

**Option 3 : Netlify CLI**
```bash
npm install -g netlify-cli
netlify login
netlify deploy --prod
```

### Étape 3 : Configuration des Variables d'Environnement

Sur le tableau de bord Netlify :

1. **Settings → Environment variables**
2. Ajouter les variables :

```
AGRIMARKET_API=https://api.agrimarket.cm/backend
STRIPE_PUBLIC_KEY=pk_live_votre_cle_publique
MTN_MOMO_API_KEY=votre_cle_mtn
```

## 🔗 Intégration des APIs Réelles

### 1️⃣ Mobile Money (MTN MoMo)

**Obtenir les clés API :**
1. Allez sur https://momoapi.mtn.com/
2. Inscrivez-vous et créez une application
3. Obtenez votre `API Key` et `Reference ID`
4. Configurez dans `.env` :

```env
MTN_MOMO_API_KEY=your_actual_api_key_here
MTN_MOMO_SUBSCRIPTION_KEY=your_subscription_key_here
```

**Tester l'intégration :**
```bash
curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/momo \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "mtn",
    "phone": "+237612345678",
    "amount": 5000,
    "external_id": "CMD-001"
  }'
```

### 2️⃣ Orange Money

**Obtenir les clés API :**
1. Allez sur https://developer.orange.com/
2. Créez une application Orange Money
3. Obtenez votre `API Key`
4. Configurez dans `.env` :

```env
ORANGE_MONEY_API_KEY=your_actual_api_key_here
```

**Tester :**
```bash
curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/momo \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "orange",
    "phone": "+237699999999",
    "amount": 5000,
    "external_id": "CMD-002"
  }'
```

### 3️⃣ Stripe (Paiement Carte)

**Obtenir les clés API :**
1. Allez sur https://stripe.com/
2. Créez un compte et connectez-vous au tableau de bord
3. Allez à **Developers → API Keys**
4. Copiez `Publishable Key` et `Secret Key`
5. Configurez dans `.env` :

```env
STRIPE_PUBLIC_KEY=pk_live_votre_cle_publique
STRIPE_SECRET_KEY=sk_live_votre_cle_secrete
```

**Tester :**
```bash
curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/stripe \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 50000,
    "token": "tok_visa",
    "currency": "XAF",
    "description": "Achat AgriMarket"
  }'
```

### 4️⃣ Email (Mailgun ou SendGrid)

**Option Mailgun :**
1. Allez sur https://www.mailgun.com/
2. Inscrivez-vous et créez un domaine
3. Obtenez votre `API Key` et `Domain`
4. Configurez dans `.env` :

```env
MAILGUN_DOMAIN=sandbox.mailgun.org
MAILGUN_API_KEY=key-your_api_key_here
MAILGUN_FROM=noreply@agrimarket.cm
```

**Option SendGrid :**
1. Allez sur https://sendgrid.com/
2. Créez un compte et un API Key
3. Configurez dans `.env` :

```env
SENDGRID_API_KEY=SG.your_api_key_here
```

**Tester :**
```bash
curl -X POST http://localhost/agrimarket/backend/index.php?path=notifications/email \
  -H "Content-Type: application/json" \
  -d '{
    "to": "client@example.com",
    "subject": "Confirmée commande",
    "body": "<h1>Merci pour votre achat</h1>"
  }'
```

### 5️⃣ SMS (Twilio ou AfricasTalking)

**Option Twilio :**
1. Allez sur https://www.twilio.com/
2. Inscrivez-vous et obtenez votre numéro de téléphone
3. Obtenez votre `Account SID` et `Auth Token`
4. Configurez dans `.env` :

```env
TWILIO_SID=your_sid_here
TWILIO_TOKEN=your_auth_token_here
TWILIO_FROM=+1234567890
```

**Option AfricasTalking :**
1. Allez sur https://africastalking.com/
2. Inscrivez-vous pour le sandbox
3. Obtenez votre `API Key` et `Username`
4. Configurez dans `.env` :

```env
AFRICAS_TALKING_KEY=your_api_key_here
AFRICAS_TALKING_USERNAME=your_username_here
```

**Tester :**
```bash
curl -X POST http://localhost/agrimarket/backend/index.php?path=notifications/sms \
  -H "Content-Type: application/json" \
  -d '{
    "to": "+237612345678",
    "text": "Votre commande a été confirmée! Ref: CMD-001"
  }'
```

## 📊 Architecture de Déploiement

```
┌─────────────────┐
│    Netlify      │ ← Front-end statique (HTML/CSS/JS)
│  (votre-app.    │
│   netlify.app)  │
└────────┬────────┘
         │ API calls
         ↓
┌─────────────────┐
│   Backend PHP   │ ← API (Vercel/Railway/Heroku)
│  (api.agri...   │
│   market.cm)    │
└────────┬────────┘
         │ Database
         ↓
┌─────────────────┐
│  MySQL/Postgre  │ ← Cloud DB (PlanetScale/AWS RDS)
│   (agrilink_v1) │
└─────────────────┘
         │
         ├──→ MTN MoMo API
         ├──→ Orange Money API
         ├──→ Stripe API
         ├──→ Mailgun/SendGrid
         └──→ Twilio/AfricasTalking
```

## 🔐 Sécurité

### Bonnes pratiques :

1. **HTTPS obligatoire** → Netlify inclut Let's Encrypt gratuitement
2. **CORS** → Configuré dans `.htaccess` pour le backend
3. **JWT Authentication** → Implémenté dans l'API
4. **Variables d'environnement** → Ne jamais committer les clés API
5. **Rate Limiting** → À implémenter pour les endpoints de paiement
6. **Validation** → Valider tous les inputs côté serveur

### Fichier `.gitignore` :
```
.env
.env.local
node_modules/
backend/data/
*.log
```

## 📈 Monitoring & Analytics

1. **Netlify Analytics** :
   - Inclus gratuitement
   - Visitez : Settings → Analytics

2. **Error Tracking** :
   - Netlify Functions logs
   - Console du navigateur (F12)

3. **API Monitoring** :
   - Dashboard backend pour les erreurs
   - Logs des transactions

## 🚀 Prochaines Étapes

1. ✅ Déployer sur Netlify
2. ✅ Configurer les APIs réelles
3. ⏳ Ajouter une application mobile (Flutter/React Native)
4. ⏳ Migrer vers PostgreSQL cloud
5. ⏳ Mettre en place le dashboard admin complet

## 📞 Support

Pour des questions sur :
- **Netlify** : https://docs.netlify.com/
- **MTN MoMo** : https://momoapi.mtn.com/docs
- **Orange Money** : https://developer.orange.com/
- **Stripe** : https://stripe.com/docs/
- **Mailgun** : https://mailgun.com/docs/
- **Twilio** : https://www.twilio.com/docs/

---

**Version** : 1.0  
**Date** : Mai 2026  
**Statut** : Production-Ready
