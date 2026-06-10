<div class="card">
    <h2>🚗 Véhicules disponibles</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (empty($vehicules)): ?>
        <p>Aucun véhicule disponible pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>Type</th>
                    <th>Kilométrage</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($v['modele']) ?></td>
                        <td><?= htmlspecialchars($v['type']) ?></td>
                        <td><?= number_format($v['km_actuel'], 0, ',', ' ') ?> km</td>
                        <td>
                            <span class="badge badge-disponible">
                                <?= htmlspecialchars($v['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/drivelog/public/index.php?page=reservation/nouvelle&id_vehicule=<?= $v['id'] ?>"
                               class="btn btn-primary">
                                Réserver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>