<?php

class User {

    // CONNEXION BDD
   
    // Comme dans Vehicule et Reservation, on centralise
    // la connexion dans une méthode privée
    private static function getConnection(): PDO {
        return getDB(); // vient de config/database.php
    }


    // PROPRIÉTÉS
    private int    $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password; 
    private string $role;     

    // CONSTRUCTEUR
    public function __construct(
        int    $id,
        string $nom,
        string $prenom,
        string $email,
        string $password,
        string $role = 'utilisateur'
    ) {
        $this->id       = $id;
        $this->nom      = $nom;
        $this->prenom   = $prenom;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }

    // GETTERS ET SETTERS
    public function getId(): int      { return $this->id; }
    public function getNom(): string  { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string  { return $this->role; }

    public function setNom(string $nom): void     { $this->nom = $nom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setRole(string $role): void   { $this->role = $role; }


    // Vérifie si l'utilisateur est admin
    public function estAdmin(): bool {
        return $this->role === 'admin';
    }

    // Connexion
    public static function login(string $email, string $password): ?array {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // connexion réussie
        }
        return null; // mauvais email ou mauvais mot de passe
    }

    // Récupère tous les utilisateurs (pour le gestionnaire)
    public static function findAll(): array {
        $pdo  = self::getConnection();
        $stmt = $pdo->query("SELECT id, nom, email, role FROM utilisateurs");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un utilisateur par son ID
    public static function findById(int $id): ?array {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("SELECT id, nom, email, role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    // Récupère un utilisateur par son email (utile pour la connexion)
    public static function findByEmail(string $email): ?array {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }


    // Crée un utilisateur   
    public static function create(array $data): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (nom, email, password, role)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'utilisateur'
        ]);
    }

    // Met à jour un utilisateur (sans toucher au mot de passe)
    public static function update(int $id, array $data): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("
            UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?
        ");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['role'],
            $id
        ]);
    }

    // Supprime un utilisateur
    public static function delete(int $id): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

