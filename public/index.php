<?php

// Point d'entrée unique de l'application
session_start();

// Chargement de la config BDD
require_once __DIR__ . '/../config/database.php';

// Chargement du router
require_once __DIR__ . '/../app/router.php';