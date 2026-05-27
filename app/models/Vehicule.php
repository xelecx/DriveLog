<?php

class Vehicule {
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

    // Besoin entretien si écart > 20 000 km
    public function besoinEntretien(): bool {
        return ($this->km_actuel - $this->km_dernier_entretien) > 20000;
    }

    public function estDisponible(): bool {
        return $this->statut === "Disponible";
    }

    // Getters
    public function getId(): int            { return $this->id; }
    public function getImmat(): string      { return $this->immatriculation; }
    public function getModele(): string     { return $this->modele; }
    public function getType(): string       { return $this->type; }
    public function getDateAchat(): string  { return $this->date_achat; }
    public function getKmActuel(): int      { return $this->km_actuel; }
    public function getStatut(): string     { return $this->statut; }

    // Setters
    public function setKmActuel(int $km): void {
        if ($km < $this->km_actuel) {
            throw new Exception("Le kilométrage ne peut pas diminuer !");
        }
        $this->km_actuel = $km;
    }

    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    // Récupère tous les véhicules depuis la BDD
    public static function getAll(): array {
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT * FROM vehicules");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un véhicule par son ID
    public static function getById(int $id): ?array {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Crée un nouveau véhicule en BDD
    public static function create(array $data): bool {
        $pdo  = getDB();
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
            $data['km_actuel'], // au départ, dernier entretien = km actuel
            'Disponible'
        ]);
    }

    // Met à jour un véhicule
    public static function update(int $id, array $data): bool {
        $pdo  = getDB();
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
        $pdo  = getDB();
        $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Véhicules qui ont besoin d'entretien (pour le gestionnaire)
    public static function getAEntretenir(): array {
        $pdo  = getDB();
        $stmt = $pdo->query("
            SELECT * FROM vehicules 
            WHERE (km_actuel - km_dernier_entretien) > 20000
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}