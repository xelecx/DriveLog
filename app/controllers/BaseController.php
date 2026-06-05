<?php

class BaseController {

    // =====================
    // RENDU D'UNE VUE
    // =====================
    // Appelé dans tous les controllers pour afficher une page
    // Exemple : $this->render('collaborateur/client', ['user' => $user])
    protected function render(string $vue, array $data = []): void {
        // extract() transforme les clés du tableau en variables
        // ['user' => $user] devient $user directement dans la vue
        extract($data);

        // Chemin vers le fichier de vue
        $chemin = __DIR__ . '/../views/' . $vue . '.php';

        if (!file_exists($chemin)) {
            die("Vue introuvable : " . $vue);
        }

        // On inclut le header, la vue, puis le footer
        require_once __DIR__ . '/../views/layout/header.php';
        require_once $chemin;
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // =====================
    // REDIRECTIONS
    // =====================
    // Redirige vers une autre page
    // Exemple : $this->redirect('vehicule/liste')
    protected function redirect(string $url): void {
        header("Location: /drivelog/public/index.php?page=" . $url);
        exit();
    }

    // =====================
    // SÉCURITÉ
    // =====================
    // Vérifie que l'utilisateur est connecté
    // Sinon on le renvoie à la page de connexion
    protected function requireLogin(): void {
        if (!isset($_SESSION['user'])) {
            $this->redirect('login');
        }
    }

    // Vérifie que l'utilisateur est admin
    // Sinon on le renvoie à son espace collaborateur
    protected function requireAdmin(): void {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('collaborateur/client');
        }
    }

    // =====================
    // DONNÉES DU FORMULAIRE
    // =====================
    // Récupère les données POST de façon sécurisée
    // htmlspecialchars() empêche les injections HTML/JS
    protected function getPost(string $key): ?string {
        if (isset($_POST[$key])) {
            return htmlspecialchars(trim($_POST[$key]));
        }
        return null;
    }

    // Récupère un paramètre GET de façon sécurisée
    protected function getParam(string $key): ?string {
        if (isset($_GET[$key])) {
            return htmlspecialchars(trim($_GET[$key]));
        }
        return null;
    }
}