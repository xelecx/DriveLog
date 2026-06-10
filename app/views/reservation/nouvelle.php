<?php
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>🚗 Nouvelle réservation</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (isset($succes)): ?>
        <div class="alert-succes"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <?php if (empty($vehicules)): ?>
        <p>Aucun véhicule disponible pour le moment.</p>
    <?php else: ?>

        <form method="POST" action="/drivelog/public/index.php?page=reservation/nouvelle">

            <div class="form-group">
                <label for="id_vehicule">Véhicule *</label>
                <select id="id_vehicule" name="id_vehicule" required>
                    <option value="">-- Choisissez un véhicule --</option>
                    <?php foreach ($vehicules as $v): ?>
                        <option value="<?= $v['id'] ?>"
                            <?= (isset($_GET['id_vehicule']) && $_GET['id_vehicule'] == $v['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['modele']) ?>
                            (<?= htmlspecialchars($v['immatriculation']) ?>) –
                            <?= htmlspecialchars($v['type']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_debut">Date de départ *</label>
                <input
                    type="date"
                    id="date_debut"
                    name="date_debut"
                    min="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="date_fin">Date de retour *</label>
                <input
                    type="date"
                    id="date_fin"
                    name="date_fin"
                    min="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="km_depart">Kilométrage au départ *</label>
                <input
                    type="number"
                    id="km_depart"
                    name="km_depart"
                    placeholder="Ex: 45000"
                    min="0"
                    required
                >
            </div>

            <div class="form-group">
                <label for="besoin">Type de besoin</label>
                <input
                    type="text"
                    id="besoin"
                    name="besoin"
                    placeholder="Ex: Besoin d'un utilitaire pour un chantier"
                >
            </div>

            <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
            <a href="/drivelog/public/index.php?page=collaborateur/client" class="btn btn-secondary">Annuler</a>

        </form>

    <?php endif; ?>
</div>

<script>
    // Empêche de choisir une date de fin avant la date de début
    document.getElementById('date_debut').addEventListener('change', function() {
        document.getElementById('date_fin').min = this.value;
    });
</script>