<?php

class Reservation {

    // =====================
    // CONNEXION BDD
    // =====================
    private static function getConnection(): PDO {
        return getDB();
    }

    // =====================
    // CONSTRUCTEUR
    // =====================
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

   
    // GETTERS ET SETTERS
    
    public function getId(): int          { return $this->id; }
    public function getIdVehicule(): int  { return $this->id_vehicule; }
    public function getIdUser(): int      { return $this->id_user; }
    public function getDateDebut(): string { return $this->date_debut; }
    public function getDateFin(): string  { return $this->date_fin; }
    public function getKmDepart(): ?int   { return $this->km_depart; }
    public function getKmRetour(): ?int   { return $this->km_retour; }
    public function getCarburant(): ?int  { return $this->carburant; }
    public function getIncident(): ?string { return $this->incident; }

    public function setKmDepart(int $km): void   { $this->km_depart = $km; }
    public function setKmRetour(int $km): void    { $this->km_retour = $km; }
    public function setCarburant(int $c): void    { $this->carburant = $c; }
    public function setIncident(string $i): void  { $this->incident = $i; }

   
    

    // Vérifie si un véhicule est dispo sur une plage de dates
    public static function vehiculeDisponible(int $id_vehicule, string $date_debut, string $date_fin): bool {
        $pdo  = self::getConnection();
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
        $pdo = self::getConnection();

        // Vérifie que le véhicule n'est pas en panne
        $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ?");
        $stmt->execute([$data['id_vehicule']]);
        $vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vehicule['statut'] === 'En panne') {
            throw new Exception("Ce véhicule est en panne, réservation impossible.");
        }

        // Vérifie la disponibilité sur les dates
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
        $pdo  = self::getConnection();

        // Vérifie que km retour >= km départ
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
    public static function findByUser(int $id_user): array {
        $pdo  = self::getConnection();
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
    public static function findAll(): array {
        $pdo  = self::getConnection();
        $stmt = $pdo->query("
            SELECT r.*, v.modele, v.immatriculation, u.nom
            FROM reservations r
            JOIN vehicules v ON r.id_vehicule = v.id
            JOIN utilisateurs u ON r.id_user = u.id
            ORDER BY r.date_debut DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère une réservation par son ID
    public static function findById(int $id): ?array {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}