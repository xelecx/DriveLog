<?php

class Vehicule {
    
    // CONNEXION BDD
    private static function getConnection(): PDO {
        return getDB(); // appelle la fonction de config/database.php
    }

    
    // CONSTRUCTEUR
    protected int    $id;
    protected string $immatriculation;
    protected string $modele;
    protected string $type;
    protected string $date_achat;
    protected int    $km_actuel;
    protected int    $km_dernier_entretien;
    protected string $statut;

    public function __construct(
        int    $id,
        string $immatriculation,
        string $modele,
        string $type,
        string $date_achat,
        int    $km_actuel,
        int    $km_dernier_entretien,
        string $statut = "Disponible"
    ) {
        $this->id                   = $id;
        $this->immatriculation      = $immatriculation;
        $this->modele               = $modele;
        $this->type                 = $type;
        $this->date_achat           = $date_achat;
        $this->km_actuel            = $km_actuel;
        $this->km_dernier_entretien = $km_dernier_entretien;
        $this->statut               = $statut;
    }

    
    // GETTERS ET SETTERS
    public function getId(): int                      { return $this->id; }
    public function getImmatriculation(): string      { return $this->immatriculation; }
    public function getModele(): string               { return $this->modele; }
    public function getType(): string                 { return $this->type; }
    public function getDateAchat(): string            { return $this->date_achat; }
    public function getKmActuel(): int                { return $this->km_actuel; }
    public function getKmDernierEntretien(): int      { return $this->km_dernier_entretien; }
    public function getStatut(): string               { return $this->statut; }

    public function setImmatriculation(string $immatriculation): void {
        $this->immatriculation = $immatriculation;
    }
    public function setModele(string $modele): void {
        $this->modele = $modele;
    }
    public function setType(string $type): void {
        $this->type = $type;
    }
    public function setKmActuel(int $km): void {
        if ($km < $this->km_actuel) {
            throw new Exception("Le kilométrage ne peut pas diminuer !");
        }
        $this->km_actuel = $km;
    }
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

   
    // MÉTHODES MÉTIER
    

    // Retourne true si écart km > 20 000 (demandé dans le sujet)
    public function besoinEntretien(): bool {
        return ($this->km_actuel - $this->km_dernier_entretien) > 20000;
    }

    // Retourne tous les véhicules
    public static function findAll(): array {
        $pdo  = self::getConnection();
        $stmt = $pdo->query("SELECT * FROM vehicules");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retourne un véhicule par son ID
    public static function findById(int $id): ?array {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Crée un véhicule en BDD
    public static function create(array $data): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO vehicules 
            (immatriculation, modele, type, date_achat, km_actuel, km_dernier_entretien, statut)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['immatriculation'],
            $data['modele'],
            $data['type'],
            $data['date_achat'],
            $data['km_actuel'],
            $data['km_actuel'], // au départ dernier entretien = km actuel
            'Disponible'
        ]);
    }

    // Met à jour un véhicule
    public static function update(int $id, array $data): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("
            UPDATE vehicules 
            SET immatriculation = ?, modele = ?, type = ?, km_actuel = ?, statut = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['immatriculation'],
            $data['modele'],
            $data['type'],
            $data['km_actuel'],
            $data['statut'],
            $id
        ]);
    }

    // Supprime un véhicule
    public static function delete(int $id): bool {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Véhicules nécessitant un entretien (écart > 20 000 km)
    public static function findNecessitantEntretien(): array {
        $pdo  = self::getConnection();
        $stmt = $pdo->query("
            SELECT * FROM vehicules 
            WHERE (km_actuel - km_dernier_entretien) > 20000
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Véhicules disponibles uniquement
    public static function findDisponibles(): array {
        $pdo  = self::getConnection();
        $stmt = $pdo->query("
            SELECT * FROM vehicules 
            WHERE statut = 'Disponible'
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}