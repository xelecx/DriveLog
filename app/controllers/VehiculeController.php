<?php

require_once __DIR__ . '/../models/Vehicule.php';

class VehiculeController extends BaseController {

    // =====================
    // LISTE DES VÉHICULES
    // (gestionnaire uniquement)
    // =====================
    public function liste(): void {
        $this->requireAdmin();

        $vehicules = Vehicule::findAll();
        $this->render('gestionnaire/parc', ['vehicules' => $vehicules]);
    }

    // =====================
    // LISTE DES VÉHICULES DISPONIBLES
    // (collaborateur)
    // =====================
    public function disponibles(): void {
        $this->requireLogin();

        $vehicules = Vehicule::findDisponibles();
        $this->render('vehicule/liste', ['vehicules' => $vehicules]);
    }

    // =====================
    // DÉTAIL D'UN VÉHICULE
    // =====================
    public function detail(): void {
        $this->requireLogin();

        $id      = (int) $this->getParam('id');
        $vehicule = Vehicule::findById($id);

        if (!$vehicule) {
            die("Véhicule introuvable.");
        }

        $this->render('vehicule/liste', ['vehicule' => $vehicule]);
    }

    // =====================
    // CRÉER UN VÉHICULE
    // (gestionnaire uniquement)
    // =====================
    public function creer(): void {
        $this->requireAdmin();

        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Vehicule::create([
                    'immatriculation' => $this->getPost('immatriculation'),
                    'modele'          => $this->getPost('modele'),
                    'type'            => $this->getPost('type'),
                    'date_achat'      => $this->getPost('date_achat'),
                    'km_actuel'       => (int) $this->getPost('km_actuel')
                ]);
                $this->redirect('gestionnaire/parc');
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('gestionnaire/parc', ['erreur' => $erreur]);
    }

    // =====================
    // MODIFIER UN VÉHICULE
    // (gestionnaire uniquement)
    // =====================
    public function modifier(): void {
        $this->requireAdmin();

        $id      = (int) $this->getParam('id');
        $vehicule = Vehicule::findById($id);

        if (!$vehicule) {
            die("Véhicule introuvable.");
        }

        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Vehicule::update($id, [
                    'immatriculation' => $this->getPost('immatriculation'),
                    'modele'          => $this->getPost('modele'),
                    'type'            => $this->getPost('type'),
                    'km_actuel'       => (int) $this->getPost('km_actuel'),
                    'statut'          => $this->getPost('statut')
                ]);
                $this->redirect('gestionnaire/parc');
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('gestionnaire/parc', [
            'vehicule' => $vehicule,
            'erreur'   => $erreur
        ]);
    }

    // =====================
    // SUPPRIMER UN VÉHICULE
    // (gestionnaire uniquement)
    // =====================
    public function supprimer(): void {
        $this->requireAdmin();

        $id = (int) $this->getParam('id');
        Vehicule::delete($id);
        $this->redirect('gestionnaire/parc');
    }
}