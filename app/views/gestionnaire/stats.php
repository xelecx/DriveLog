<?php
/** @var array $stats_vehicules */
?>

<div class="card">
    <h2>📊 Statistiques de la flotte</h2>

    <h3 style="margin:20px 0 12px; color:#1a1a2e;">Taux d'utilisation par véhicule</h3>
    <table>
        <thead>
            <tr>
                <th>Modèle</th>
                <th>Immatriculation</th>
                <th>Nb réservations</th>
                <th>KM total parcouru</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats_vehicules as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['modele']) ?></td>
                    <td><?= htmlspecialchars($s['immatriculation']) ?></td>
                    <td><?= $s['nb_reservations'] ?></td>
                    <td><?= $s['km_total'] ? number_format($s['km_total'], 0, ',', ' ') . ' km' : '–' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 style="margin:30px 0 12px; color:#1a1a2e;">Consommation moyenne carburant / batterie</h3>
    <table>
        <thead>
            <tr>
                <th>Modèle</th>
                <th>Immatriculation</th>
                <th>Niveau moyen au retour</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($stats_carburant)): ?>
                <tr><td colspan="3">Pas encore de données.</td></tr>
            <?php else: ?>
                <?php foreach ($stats_carburant as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['modele']) ?></td>
                        <td><?= htmlspecialchars($s['immatriculation']) ?></td>
                        <td><?= $s['carburant_moyen'] ?>%</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>