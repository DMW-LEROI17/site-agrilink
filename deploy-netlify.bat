@echo off
REM Script de déploiement Netlify pour AgriMarket
REM Utilisation : Double-cliquez sur ce fichier ou exécutez depuis CMD

echo ========================================
echo 🚀 AgriMarket - Déploiement Netlify
echo ========================================
echo.

REM Vérifier si Git est installé
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Git n'est pas installé !
    echo Téléchargez-le depuis https://git-scm.com/
    pause
    exit /b 1
)

echo ✅ Git détecté
echo.

REM Initialiser Git si pas déjà fait
if not exist .git (
    echo 📝 Initialisation du repository Git...
    git init
    echo ✅ Repository initialisé
) else (
    echo ✅ Repository Git déjà initialisé
)

echo.
echo 📤 Ajout des fichiers...
git add .

echo.
echo 💾 Création du commit...
git commit -m "Deploy AgriMarket to Netlify - %date% %time%"

echo.
echo 🔗 Configuration du remote GitHub...
echo IMPORTANT : Remplacez VOTRE_USERNAME par votre nom d'utilisateur GitHub
set /p username="Entrez votre nom d'utilisateur GitHub : "
git remote add origin https://github.com/%username%/agrimarket-netlify.git 2>nul

echo.
echo 🚀 Push vers GitHub...
git push -u origin main

if %errorlevel% equ 0 (
    echo.
    echo ✅ Succès ! Votre code est sur GitHub
    echo.
    echo 📋 Prochaines étapes :
    echo 1. Allez sur https://app.netlify.com/
    echo 2. "New site from Git" → GitHub
    echo 3. Sélectionnez "agrimarket-netlify"
    echo 4. Build command : echo 'Static site ready'
    echo 5. Publish directory : .
    echo 6. Deploy !
    echo.
    echo 📚 Guide complet : DEPLOY_NETLIFY_GUIDE.md
) else (
    echo.
    echo ❌ Erreur lors du push. Vérifiez :
    echo - Votre nom d'utilisateur GitHub
    echo - Les permissions du repository
    echo - Votre connexion internet
)

echo.
pause