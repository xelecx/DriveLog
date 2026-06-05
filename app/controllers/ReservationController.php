<?php

require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Vehicule.php';

class ReservationController extends BaseController {

    // =====================
    // NOUVELLE RÉSERVATION
    // (collaborateur)
    // =====================
    // Affiche le formulaire et traite la réservation
    public function nouvelle(): void {
        $this->requireLogin();

        // On récupère les véhicules disponibles pour le formulaire
        $vehicules = Vehicule::findDisponibles();
        $erreur    = null;
        $succes    = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Reservation::create([
                    'id_vehicule' => (int) $this->getPost('id_vehicule'),
                    'id_user'     => $_SESSION['user']['id'], // l'utilisateur connecté
                    'date_debut'  => $this->getPost('date_debut'),
                    'date_fin'    => $this->getPost('date_fin'),
                    'km_depart'   => (int) $this->getPost('km_depart')
                ]);
                $succes = "Réservation effectuée avec succès !";
                // On recharge les véhicules disponibles après réservation
                $vehicules = Vehicule::findDisponibles();
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('reservation/nouvelle', [
            'vehicules' => $vehicules,
            'erreur'    => $erreur,
            'succes'    => $succes
        ]);
    }

    // =====================
    // MES TRAJETS
    // (collaborateur)
    // =====================
    // Historique personnel des réservations
    public function mesTrajets(): void {
        $this->requireLogin();

        $reservations = Reservation::findByUser($_SESSION['user']['id']);
        $this->render('collaborateur/trajet', ['reservations' => $reservations]);
    }

    // =====================
    // FICHE DE RETOUR
    // (collaborateur)
    // =====================
    // L'employé saisit le km retour, carburant, incident
    public function retour(): void {
        $this->requireLogin();

        $id          = (int) $this->getParam('id');
        $reservation = Reservation::findById($id);
        $erreur      = null;
        $succes      = null;

        if (!$reservation) {
            die("Réservation introuvable.");
        }

        // Sécurité : un employé ne peut voir que SES réservations
        if ($reservation['id_user'] !== $_SESSION['user']['id']) {
            die("Accès interdit.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Reservation::enregistrerRetour($id, [
                    'km_retour' => (int) $this->getPost('km_retour'),
                    'carburant' => (int) $this->getPost('carburant'),
                    'incident'  => $this->getPost('incident')
                ]);
                $succes = "Retour enregistré avec succès !";
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('collaborateur/retour', [
            'reservation' => $reservation,
            'erreur'      => $erreur,
            'succes'      => $succes
        ]);
    }

    // =====================
    // TOUTES LES RÉSERVATIONS
    // (gestionnaire uniquement)
    // =====================
    public function liste(): void {
        $this->requireAdmin();

        $reservations = Reservation::findAll();
        $this->render('reservation/liste', ['reservations' => $reservations]);
    }

    // =====================
    // DÉTAIL D'UNE RÉSERVATION
    // (gestionnaire uniquement)
    // =====================
    public function detail(): void {
        $this->requireAdmin();

        $id          = (int) $this->getParam('id');
        $reservation = Reservation::findById($id);

        if (!$reservation) {
            die("Réservation introuvable.");
        }

        $this->render('reservation/detail', ['reservation' => $reservation]);
    }
}