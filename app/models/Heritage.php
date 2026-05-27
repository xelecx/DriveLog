<?php


// CLASSE UTILITAIRE
// Hérite de Vehicule

class Utilitaire extends Vehicule {

    // Propriété spécifique aux utilitaires
    private float $charge_max; // en tonnes (ex: 1.5 pour 1.5 tonne)

    
    // CONSTRUCTEUR
   
    public function __construct(
        int    $id,
        string $immatriculation,
        string $modele,
        string $date_achat,
        int    $km_actuel,
        int    $km_dernier_entretien,
        float  $charge_max,            // paramètre en plus
        string $statut = "Disponible"
    ) {
        // On appelle le constructeur de Vehicule pour les infos communes
        // "type" est forcé à "utilitaire" automatiquement
        parent::__construct(
            $id,
            $immatriculation,
            $modele,
            'utilitaire',
            $date_achat,
            $km_actuel,
            $km_dernier_entretien,
            $statut
        );

        // On initialise la propriété spécifique à Utilitaire
        $this->charge_max = $charge_max;
    }

    
    // GETTER
    
    public function getChargeMax(): float {
        return $this->charge_max;
    }

    
    // MÉTHODE MÉTIER
    
    // Vérifie si l'utilitaire peut porter un certain poids
    public function peutPorter(float $poids_tonnes): bool {
        return $poids_tonnes <= $this->charge_max;
    }
}



// CLASSE VOITUREELECTRIQUE
// Hérite de Vehicule

class VoitureElectrique extends Vehicule {

    // Propriété spécifique aux électriques
    private int $autonomie_km; // autonomie maximale en km

    
    // CONSTRUCTEUR
   
    public function __construct(
        int    $id,
        string $immatriculation,
        string $modele,
        string $date_achat,
        int    $km_actuel,
        int    $km_dernier_entretien,
        int    $autonomie_km,          // paramètre en plus
        string $statut = "Disponible"
    ) {
        
        parent::__construct(
            $id,
            $immatriculation,
            $modele,
            'electrique',
            $date_achat,
            $km_actuel,
            $km_dernier_entretien,
            $statut
        );

        $this->autonomie_km = $autonomie_km;
    }

    // GETTER
    public function getAutonomie(): int {
        return $this->autonomie_km;
    }

    // MÉTHODES MÉTIER
    // Vérifie si la voiture peut faire un trajet sans recharger
    public function autonomieSuffisante(int $distance_km): bool {
        return $this->autonomie_km >= $distance_km;
    }

    // Retourne le pourcentage de batterie restant après un trajet
    public function batterieRestante(int $distance_parcourue): float {
        if ($distance_parcourue >= $this->autonomie_km) {
            return 0.0; // batterie vide
        }
        return round(
            (($this->autonomie_km - $distance_parcourue) / $this->autonomie_km) * 100,
            1
        );
    }
}