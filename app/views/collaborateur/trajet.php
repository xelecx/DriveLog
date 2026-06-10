<div class="card">
    <h2>📋 Mes trajets</h2>

    <?php if (empty($reservations)): ?>
        <p>Vous n'avez effectué aucun trajet pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Véhicule</th>
                    <th>Immatriculation</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>KM départ</th>
                    <th>KM retour</th>
                    <th>Carburant</th>
                    <th>Incident</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['modele']) ?></td>
                        <td><?= htmlspecialchars($r['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($r['date_debut']) ?></td>
                        <td><?= htmlspecialchars($r['date_fin']) ?></td>
                        <td><?= $r['km_depart'] ? number_format($r['km_depart'], 0, ',', ' ') . ' km' : '–' ?></td>
                        <td><?= $r['km_retour'] ? number_format($r['km_retour'], 0, ',', ' ') . ' km' : '–' ?></td>
                        <td><?= $r['carburant'] ? $r['carburant'] . '%' : '–' ?></td>
                        <td><?= $r['incident'] ? htmlspecialchars($r['incident']) : '–' ?></td>
                        <td>
                            <?php if (!$r['km_retour']): ?>
                                <a href="/drivelog/public/index.php?page=collaborateur/retour&id=<?= $r['id'] ?>"
                                   class="btn btn-warning">
                                    Retour
                                </a>
                            <?php else: ?>
                                <span style="color: #27ae60;">✓ Terminé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>