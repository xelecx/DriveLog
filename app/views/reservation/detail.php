<?php
/** @var array $reservation */
?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2>🔍 Détail de la réservation #<?= $reservation['id'] ?></h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">

        <!-- Infos véhicule -->
        <div style="background:#f8f8f8; padding:16px; border-radius:6px;">
            <h3 style="margin-bottom:12px; color:#1a1a2e;">🚗 Véhicule</h3>
            <p><strong>Modèle :</strong> <?= htmlspecialchars($reservation['modele']) ?></p>
            <p><strong>Immatriculation :</strong> <?= htmlspecialchars($reservation['immatriculation']) ?></p>
        </div>

        <!-- Infos collaborateur -->
        <div style="background:#f8f8f8; padding:16px; border-radius:6px;">
            <h3 style="margin-bottom:12px; color:#1a1a2e;">👤 Collaborateur</h3>
            <p><strong>Nom :</strong> <?= htmlspecialchars($reservation['nom']) ?></p>
        </div>

        <!-- Infos dates -->
        <div style="background:#f8f8f8; padding:16px; border-radius:6px;">
            <h3 style="margin-bottom:12px; color:#1a1a2e;">📅 Dates</h3>
            <p><strong>Départ :</strong> <?= htmlspecialchars($reservation['date_debut']) ?></p>
            <p><strong>Retour :</strong> <?= htmlspecialchars($reservation['date_fin']) ?></p>
        </div>

        <!-- Infos kilométrage -->
        <div style="background:#f8f8f8; padding:16px; border-radius:6px;">
            <h3 style="margin-bottom:12px; color:#1a1a2e;">📏 Kilométrage</h3>
            <p><strong>KM départ :</strong>
                <?= $reservation['km_depart'] ? number_format($reservation['km_depart'], 0, ',', ' ') . ' km' : '–' ?>
            </p>
            <p><strong>KM retour :</strong>
                <?= $reservation['km_retour'] ? number_format($reservation['km_retour'], 0, ',', ' ') . ' km' : '–' ?>
            </p>
            <?php if ($reservation['km_depart'] && $reservation['km_retour']): ?>
                <p><strong>Distance parcourue :</strong>
                    <?= number_format($reservation['km_retour'] - $reservation['km_depart'], 0, ',', ' ') ?> km
                </p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Carburant & Incident -->
    <div style="background:#f8f8f8; padding:16px; border-radius:6px; margin-bottom:24px;">
        <h3 style="margin-bottom:12px; color:#1a1a2e;">⚠️ Retour</h3>
        <p><strong>Carburant / Batterie :</strong>
            <?= $reservation['carburant'] ? $reservation['carburant'] . '%' : '–' ?>
        </p>
        <p><strong>Incident :</strong>
            <?php if ($reservation['incident']): ?>
                <span style="color:#c0392b;"><?= htmlspecialchars($reservation['incident']) ?></span>
            <?php else: ?>
                <span style="color:#27ae60;">Aucun incident</span>
            <?php endif; ?>
        </p>
    </div>

    <a href="/drivelog/public/index.php?page=reservation/liste" class="btn btn-secondary">
        ← Retour à la liste
    </a>
</div>