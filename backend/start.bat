@echo off
chcp 65001 >nul
title AgriMarket API Server

echo.
echo ╔═══════════════════════════════════════════════════╗
echo ║   🌾 AGRIMARKET CAMEROUN - API Server             ║
echo ╚═══════════════════════════════════════════════════╝
echo.

REM Vérifier si PHP est installé
php --version >nul 2>&1
if errorlevel 1 (
    echo ❌ PHP n'est pas installé sur votre PC!
    echo.
    echo Veuillez installer XAMPP ou WAMP:
    echo https://www.apachefriends.org/index.html
    echo.
    pause
    exit /b 1
)

REM Créer le dossier data si inexistant
if not exist "data" mkdir data

echo ✅ PHP détecté
echo.
echo Démarrage du serveur API...
echo.
echo Endpoints disponibles:
echo   - http://localhost:8000/api/produits
echo   - http://localhost:8000/api/categories
echo   - http://localhost:8000/api/phyto
echo   - http://localhost:8000/api/projets
echo   - http://localhost:8000/api/commandes
echo.
echo Appuyez sur Ctrl+C pour arrêter le serveur
echo.

REM Démarrer le serveur PHP
php -S localhost:8000 -t .. index.php

pause