<?php
// Tout passe par ici, c'est le "routeur" principal
session_start();

// On charge la config de la BDD
require_once '../config/database.php';

// Pour l'instant on affiche juste ça pour tester
echo "DriveLog fonctionne !";