<?php

require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/VehiculeController.php';
require_once __DIR__ . '/controllers/ReservationController.php';
require_once __DIR__ . '/controllers/MaintenanceController.php';
require_once __DIR__ . '/models/Vehicule.php';
require_once __DIR__ . '/models/Reservation.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Heritage.php';

// On récupère la page demandée dans l'URL
// Exemple : index.php?page=vehicule/liste
// Si rien → on redirige vers login par défaut
$page = $_GET['page'] ?? 'login';

// On instancie les controllers
$userController        = new UserController();
$vehiculeController    = new VehiculeController();
$reservationController = new ReservationController();
$maintenanceController = new MaintenanceController();

// =====================
// ROUTAGE
// =====================
switch ($page) {

    // --- AUTHENTIFICATION ---
    case 'login':
        $userController->login();
        break;

    case 'logout':
        $userController->logout();
        break;

    // --- COLLABORATEUR ---
    case 'collaborateur/client':
        $vehiculeController->disponibles();
        break;

    case 'collaborateur/trajet':
        $reservationController->mesTrajets();
        break;

    case 'collaborateur/retour':
        $reservationController->retour();
        break;

    // --- RÉSERVATIONS ---
    case 'reservation/nouvelle':
        $reservationController->nouvelle();
        break;

    case 'reservation/liste':
        $reservationController->liste();
        break;

    case 'reservation/detail':
        $reservationController->detail();
        break;

    // --- VÉHICULES ---
    case 'vehicule/liste':
        $vehiculeController->disponibles();
        break;

    // --- GESTIONNAIRE ---
    case 'gestionnaire/parc':
        $vehiculeController->liste();
        break;

    case 'gestionnaire/parc/creer':
        $vehiculeController->creer();
        break;

    case 'gestionnaire/parc/modifier':
        $vehiculeController->modifier();
        break;

    case 'gestionnaire/parc/supprimer':
        $vehiculeController->supprimer();
        break;

    case 'gestionnaire/user':
        $userController->liste();
        break;

    case 'gestionnaire/user/creer':
        $userController->creer();
        break;

    case 'gestionnaire/user/modifier':
        $userController->modifier();
        break;

    case 'gestionnaire/user/supprimer':
        $userController->supprimer();
        break;

    case 'gestionnaire/maintenance':
        $maintenanceController->liste();
        break;

    case 'gestionnaire/maintenance/valider':
        $maintenanceController->valider();
        break;

    case 'gestionnaire/maintenance/statut':
        $maintenanceController->changerStatut();
        break;

    case 'gestionnaire/stats':
        $maintenanceController->statistiques();
        break;

    // --- PAGE INTROUVABLE ---
    default:
        http_response_code(404);
        die("Page introuvable : " . htmlspecialchars($page));
}