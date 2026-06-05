<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends BaseController {

    // =====================
    // CONNEXION
    // =====================
    // Affiche le formulaire de connexion (GET)
    // ou traite la connexion (POST)
    public function login(): void {
        // Si déjà connecté, on redirige direct
        if (isset($_SESSION['user'])) {
            $this->redirectSelon($_SESSION['user']['role']);
        }

        $erreur = null;

        // Le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $this->getPost('email');
            $password = $this->getPost('password');

            // On appelle la méthode login du Model
            $user = User::login($email, $password);

            if ($user) {
                // Connexion réussie : on stocke l'utilisateur en session
                $_SESSION['user'] = $user;
                $this->redirectSelon($user['role']);
            } else {
                $erreur = "Email ou mot de passe incorrect.";
            }
        }

        // Affiche la vue login avec l'erreur éventuelle
        $this->render('login', ['erreur' => $erreur]);
    }

    // =====================
    // DÉCONNEXION
    // =====================
    public function logout(): void {
        // On détruit toute la session
        session_destroy();
        $this->redirect('login');
    }

    // =====================
    // LISTE DES UTILISATEURS
    // (gestionnaire uniquement)
    // =====================
    public function liste(): void {
        $this->requireAdmin();

        $utilisateurs = User::findAll();
        $this->render('gestionnaire/user', ['utilisateurs' => $utilisateurs]);
    }

    // =====================
    // CRÉER UN UTILISATEUR
    // =====================
    public function creer(): void {
        $this->requireAdmin();

        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                User::create([
                    'nom'      => $this->getPost('nom'),
                    'email'    => $this->getPost('email'),
                    'password' => $this->getPost('password'),
                    'role'     => $this->getPost('role')
                ]);
                $this->redirect('gestionnaire/user');
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('gestionnaire/user', ['erreur' => $erreur]);
    }

    // =====================
    // MODIFIER UN UTILISATEUR
    // =====================
    public function modifier(): void {
        $this->requireAdmin();

        $id   = (int) $this->getParam('id');
        $user = User::findById($id);

        if (!$user) {
            die("Utilisateur introuvable.");
        }

        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                User::update($id, [
                    'nom'   => $this->getPost('nom'),
                    'email' => $this->getPost('email'),
                    'role'  => $this->getPost('role')
                ]);
                $this->redirect('gestionnaire/user');
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        $this->render('gestionnaire/user', [
            'user'   => $user,
            'erreur' => $erreur
        ]);
    }

    // =====================
    // SUPPRIMER UN UTILISATEUR
    // =====================
    public function supprimer(): void {
        $this->requireAdmin();

        $id = (int) $this->getParam('id');
        User::delete($id);
        $this->redirect('gestionnaire/user');
    }

    // =====================
    // MÉTHODE PRIVÉE
    // =====================
    // Redirige selon le rôle après connexion
    private function redirectSelon(string $role): void {
        if ($role === 'admin') {
            $this->redirect('gestionnaire/parc');
        } else {
            $this->redirect('collaborateur/client');
        }
    }
}