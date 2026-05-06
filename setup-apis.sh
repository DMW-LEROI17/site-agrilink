#!/bin/bash
# Script d'installation des APIs pour AgriMarket
# Utilisation : ./setup-apis.sh

echo "🚀 AgriMarket - Configuration des APIs"
echo "========================================"
echo ""

# Créer le fichier .env s'il n'existe pas
if [ ! -f backend/.env ]; then
    echo "📝 Création du fichier .env..."
    cp backend/.env.example backend/.env
    echo "✅ Fichier .env créé depuis .env.example"
else
    echo "✅ Fichier .env existe déjà"
fi

echo ""
echo "🔧 Configuration requise :"
echo ""
echo "1️⃣  Mobile Money (MTN MoMo) :"
echo "   - Site : https://momoapi.mtn.com/"
echo "   - Variables : MTN_MOMO_API_KEY, MTN_MOMO_SUBSCRIPTION_KEY"
echo ""

echo "2️⃣  Mobile Money (Orange Money) :"
echo "   - Site : https://developer.orange.com/"
echo "   - Variable : ORANGE_MONEY_API_KEY"
echo ""

echo "3️⃣  Paiement Carte (Stripe) :"
echo "   - Site : https://stripe.com/"
echo "   - Variables : STRIPE_PUBLIC_KEY, STRIPE_SECRET_KEY"
echo ""

echo "4️⃣  Email (SendGrid) :"
echo "   - Site : https://sendgrid.com/"
echo "   - Variable : SENDGRID_API_KEY"
echo ""

echo "5️⃣  Email (Mailgun) :"
echo "   - Site : https://mailgun.com/"
echo "   - Variables : MAILGUN_DOMAIN, MAILGUN_API_KEY, MAILGUN_FROM"
echo ""

echo "6️⃣  SMS (Twilio) :"
echo "   - Site : https://twilio.com/"
echo "   - Variables : TWILIO_SID, TWILIO_TOKEN, TWILIO_FROM"
echo ""

echo "7️⃣  SMS (AfricasTalking) :"
echo "   - Site : https://africastalking.com/"
echo "   - Variables : AFRICAS_TALKING_KEY, AFRICAS_TALKING_USERNAME"
echo ""

echo "📋 Étapes pour configurer :"
echo "1. Visitez chaque site et inscrivez-vous"
echo "2. Obtenez les clés API"
echo "3. Modifiez backend/.env avec vos clés"
echo "4. Testez chaque endpoint"
echo ""

echo "🧪 Pour tester les endpoints :"
echo ""
echo "MTN MoMo :"
echo "curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/momo \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"provider\":\"mtn\",\"phone\":\"+237612345678\",\"amount\":5000,\"external_id\":\"TEST-001\"}'"
echo ""

echo "Orange Money :"
echo "curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/momo \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"provider\":\"orange\",\"phone\":\"+237699999999\",\"amount\":5000,\"external_id\":\"TEST-002\"}'"
echo ""

echo "Stripe :"
echo "curl -X POST http://localhost/agrimarket/backend/index.php?path=payments/stripe \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"amount\":50000,\"token\":\"tok_visa\",\"currency\":\"XAF\",\"description\":\"Test\"}'"
echo ""

echo "Email :"
echo "curl -X POST http://localhost/agrimarket/backend/index.php?path=notifications/email \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"to\":\"test@example.com\",\"subject\":\"Test\",\"body\":\"Ceci est un test\"}'"
echo ""

echo "SMS :"
echo "curl -X POST http://localhost/agrimarket/backend/index.php?path=notifications/sms \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"to\":\"+237612345678\",\"text\":\"Ceci est un test SMS\"}'"
echo ""

echo "✅ Configuration terminée!"
echo "📚 Pour plus d'infos, voir NETLIFY_DEPLOYMENT.md"
