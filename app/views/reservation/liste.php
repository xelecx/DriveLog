<?php
?>

<div class="card">
    <h2>📋 Toutes les réservations</h2>

    <?php if (empty($reservations)): ?>
        <p>Aucune réservation pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Collaborateur</th>
                    <th>Véhicule</th>
                    <th>Immatriculation</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>KM départ</th>
                    <th>KM retour</th>
                    <th>Incident</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td>#<?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['nom']) ?></td>
                        <td><?= htmlspecialchars($r['modele']) ?></td>
                        <td><?= htmlspecialchars($r['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($r['date_debut']) ?></td>
                        <td><?= htmlspecialchars($r['date_fin']) ?></td>
                        <td><?= $r['km_depart'] ? number_format($r['km_depart'], 0, ',', ' ') . ' km' : '–' ?></td>
                        <td><?= $r['km_retour'] ? number_format($r['km_retour'], 0, ',', ' ') . ' km' : '–' ?></td>
                        <td><?= $r['incident'] ? htmlspecialchars($r['incident']) : '–' ?></td>
                        <td>
                            <a href="/drivelog/public/index.php?page=reservation/detail&id=<?= $r['id'] ?>"
                               class="btn btn-secondary">
                                Détail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>