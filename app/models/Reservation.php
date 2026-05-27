<?php

class Reservation {
    private int     $id;
    private int     $id_vehicule;
    private int     $id_user;
    private string  $date_debut;
    private string  $date_fin;
    private ?int    $km_depart;
    private ?int    $km_retour;
    private ?int    $carburant;
    private ?string $incident;

    public function __construct(
        int     $id,
        int     $id_vehicule,
        int     $id_user,
        string  $date_debut,
        string  $date_fin,
        ?int    $km_depart  = null,
        ?int    $km_retour  = null,
        ?int    $carburant  = null,
        ?string $incident   = null
    ) {
        $this->id          = $id;
        $this->id_vehicule = $id_vehicule;
        $this->id_user     = $id_user;
        $this->date_debut  = $date_debut;
        $this->date_fin    = $date_fin;
        $this->km_depart   = $km_depart;
        $this->km_retour   = $km_retour;
        $this->carburant   = $carburant;
        $this->incident    = $incident;
    }

    // Vérifie si un véhicule est dispo sur une plage de dates
    public static function vehiculeDisponible(int $id_vehicule, string $date_debut, string $date_fin): bool {
        $pdo  = getDB();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM reservations
            WHERE id_vehicule = ?
            AND date_debut < ?
            AND date_fin    > ?
        ");
        $stmt->execute([$id_vehicule, $date_fin, $date_debut]);
        return $stmt->fetchColumn() == 0;
    }

    // Crée une réservation
    public static function create(array $data): bool {
        // Vérifie que le véhicule n'est pas en panne
        $pdo     = getDB();
        $stmt    = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ?");
        $stmt->execute([$data['id_vehicule']]);
        $vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vehicule['statut'] === 'En panne') {
            throw new Exception("Ce véhicule est en panne, réservation impossible.");
        }

        // Vérifie la disponibilité
        if (!self::vehiculeDisponible($data['id_vehicule'], $data['date_debut'], $data['date_fin'])) {
            throw new Exception("Ce véhicule est déjà réservé sur cette période.");
        }

        $stmt = $pdo->prepare("
            INSERT INTO reservations (id_vehicule, id_user, date_debut, date_fin, km_depart)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['id_vehicule'],
            $data['id_user'],
            $data['date_debut'],
            $data['date_fin'],
            $data['km_depart'] ?? null
        ]);
    }

    // Enregistre le retour du véhicule
    public static function enregistrerRetour(int $id, array $data): bool {
        // Vérifie que km retour >= km départ
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT km_depart FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        $resa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data['km_retour'] < $resa['km_depart']) {
            throw new Exception("Le kilométrage de retour ne peut pas être inférieur au kilométrage de départ.");
        }

        $stmt = $pdo->prepare("
            UPDATE reservations 
            SET km_retour = ?, carburant = ?, incident = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['km_retour'],
            $data['carburant'],
            $data['incident'] ?? null,
            $id
        ]);
    }

    // Historique des trajets d'un utilisateur
    public static function getByUser(int $id_user): array {
        $pdo  = getDB();
        $stmt = $pdo->prepare("
            SELECT r.*, v.modele, v.immatriculation 
            FROM reservations r
            JOIN vehicules v ON r.id_vehicule = v.id
            WHERE r.id_user = ?
            ORDER BY r.date_debut DESC
        ");
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toutes les réservations (pour le gestionnaire)
    public static function getAll(): array {
        $pdo  = getDB();
        $stmt = $pdo->query("
            SELECT r.*, v.modele, v.immatriculation, u.nom
            FROM reservations r
            JOIN vehicules v ON r.id_vehicule = v.id
            JOIN utilisateurs u ON r.id_user = u.id
            ORDER BY r.date_debut DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}