<?php

require_once __DIR__ . '/../models/Vehicule.php';
require_once __DIR__ . '/../models/Reservation.php';

class MaintenanceController extends BaseController {

    // =====================
    // LISTE DES VÉHICULES
    // NÉCESSITANT UN ENTRETIEN
    // =====================
    // Affiche tous les véhicules dont l'écart
    // km_actuel - km_dernier_entretien > 20 000
    public function liste(): void {
        $this->requireAdmin();

        $vehicules = Vehicule::findNecessitantEntretien();
        $this->render('gestionnaire/maintenance', ['vehicules' => $vehicules]);
    }

    // =====================
    // VALIDER UN ENTRETIEN
    // =====================
    // Quand le gestionnaire confirme qu'un véhicule
    // vient d'être révisé, on remet le compteur à zéro
    public function valider(): void {
        $this->requireAdmin();

        $id      = (int) $this->getParam('id');
        $vehicule = Vehicule::findById($id);

        if (!$vehicule) {
            die("Véhicule introuvable.");
        }

        try {
            // On met à jour km_dernier_entretien = km_actuel
            // Le véhicule ne nécessite plus d'entretien
            Vehicule::update($id, [
                'immatriculation'      => $vehicule['immatriculation'],
                'modele'               => $vehicule['modele'],
                'type'                 => $vehicule['type'],
                'km_actuel'            => $vehicule['km_actuel'],
                'km_dernier_entretien' => $vehicule['km_actuel'], // reset !
                'statut'               => 'Disponible'
            ]);
            $this->redirect('gestionnaire/maintenance');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // =====================
    // STATISTIQUES
    // =====================
    // Taux d'utilisation et consommation moyenne
    // demandés dans le sujet
    public function statistiques(): void {
        $this->requireAdmin();

        $pdo = getDB();

        // Taux d'utilisation : nb de réservations par véhicule
        $stmt = $pdo->query("
            SELECT v.modele, v.immatriculation,
                   COUNT(r.id) AS nb_reservations,
                   SUM(r.km_retour - r.km_depart) AS km_total
            FROM vehicules v
            LEFT JOIN reservations r ON v.id = r.id_vehicule
            GROUP BY v.id
            ORDER BY nb_reservations DESC
        ");
        $stats_vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consommation moyenne de carburant par véhicule
        $stmt = $pdo->query("
            SELECT v.modele, v.immatriculation,
                   ROUND(AVG(r.carburant), 1) AS carburant_moyen
            FROM vehicules v
            LEFT JOIN reservations r ON v.id = r.id_vehicule
            WHERE r.carburant IS NOT NULL
            GROUP BY v.id
        ");
        $stats_carburant = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('gestionnaire/stats', [
            'stats_vehicules' => $stats_vehicules,
            'stats_carburant' => $stats_carburant
        ]);
    }

    // =====================
    // CHANGER LE STATUT
    // D'UN VÉHICULE
    // =====================
    // Ex: passer un véhicule "En panne" à "Disponible"
    // après réparation
    public function changerStatut(): void {
        $this->requireAdmin();

        $id      = (int) $this->getParam('id');
        $statut  = $this->getPost('statut');
        $vehicule = Vehicule::findById($id);

        if (!$vehicule) {
            die("Véhicule introuvable.");
        }

        try {
            Vehicule::update($id, [
                'immatriculation'      => $vehicule['immatriculation'],
                'modele'               => $vehicule['modele'],
                'type'                 => $vehicule['type'],
                'km_actuel'            => $vehicule['km_actuel'],
                'km_dernier_entretien' => $vehicule['km_dernier_entretien'],
                'statut'               => $statut
            ]);
            $this->redirect('gestionnaire/maintenance');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}