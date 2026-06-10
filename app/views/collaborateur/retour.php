<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>📝 Fiche de retour</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (isset($succes)): ?>
        <div class="alert-succes"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <!-- Infos de la réservation -->
    <div style="background:#f8f8f8; padding:16px; border-radius:6px; margin-bottom:24px;">
        <p><strong>Véhicule :</strong> <?= htmlspecialchars($reservation['modele']) ?></p>
        <p><strong>Immatriculation :</strong> <?= htmlspecialchars($reservation['immatriculation']) ?></p>
        <p><strong>KM départ :</strong> <?= number_format($reservation['km_depart'], 0, ',', ' ') ?> km</p>
    </div>

    <form method="POST" action="/drivelog/public/index.php?page=collaborateur/retour&id=<?= $reservation['id'] ?>">

        <div class="form-group">
            <label for="km_retour">Kilométrage final *</label>
            <input
                type="number"
                id="km_retour"
                name="km_retour"
                min="<?= $reservation['km_depart'] ?>"
                placeholder="Ex: 45200"
                required
            >
        </div>

        <div class="form-group">
            <label for="carburant">Niveau carburant / batterie (%) *</label>
            <input
                type="number"
                id="carburant"
                name="carburant"
                min="0"
                max="100"
                placeholder="Ex: 75"
                required
            >
        </div>

        <div class="form-group">
            <label for="incident">Incident éventuel</label>
            <textarea
                id="incident"
                name="incident"
                rows="3"
                placeholder="Ex: Rayure portière droite (laisser vide si aucun incident)"
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer le retour</button>
        <a href="/drivelog/public/index.php?page=collaborateur/trajet" class="btn btn-secondary">Annuler</a>

    </form>
</div>