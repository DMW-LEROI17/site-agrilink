@echo off
chcp 65001 >nul
title AgriMarket Cameroun - Serveur

echo ================================================
echo   AGRIMARKET CAMEROUN - Serveur PHP
echo ================================================
echo.

REM Vérifier si PHP est installé
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] PHP n'est pas installé ou pas dans le PATH
    echo.
    echo Veuillez installer XAMPP ou WAMP:
    echo https://www.apachefriends.org
    echo.
    pause
    exit /b 1
)

echo [OK] PHP détecté
echo.

REM Démarrer le serveur
echo Démarrage du serveur sur http://localhost:8000
echo Appuyez sur Ctrl+C pour arrêter
echo.

cd /d "%~dp0SIT AGRILINK\api"
php -S localhost:8000 -t .. index.php

pause