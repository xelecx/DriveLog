<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'drivelog');
define('DB_USER', 'root');
define('DB_PASS', ''); // vide par défaut sur WAMP

function getDB(): PDO {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS
        );
        // Affiche les erreurs SQL clairement pendant le dev
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}