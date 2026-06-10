<?php
?>

<div class="card">
    <h2>Suivi de maintenance</h2>

    <?php if (empty($vehicules)): ?>
        <div class="alert-succes">✓ Aucun véhicule ne nécessite d'entretien pour le moment.</div>
    <?php else: ?>
        <p style="margin-bottom:16px; color:#c0392b;">
             <?= count($vehicules) ?> véhicule(s) nécessitent une révision.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>KM actuel</th>
                    <th>Dernier entretien</th>
                    <th>Écart</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($v['modele']) ?></td>
                        <td><?= number_format($v['km_actuel'], 0, ',', ' ') ?> km</td>
                        <td><?= number_format($v['km_dernier_entretien'], 0, ',', ' ') ?> km</td>
                        <td style="color:#c0392b; font-weight:bold;">
                            +<?= number_format($v['km_actuel'] - $v['km_dernier_entretien'], 0, ',', ' ') ?> km
                        </td>
                        <td>
                            <span class="badge badge-en-panne">Révision requise</span>
                        </td>
                        <td style="display:flex; gap:6px;">
                            <a href="/drivelog/public/index.php?page=gestionnaire/maintenance/valider&id=<?= $v['id'] ?>"
                               class="btn btn-success"
                               onclick="return confirm('Confirmer la révision de ce véhicule ?')">
                                ✓ Révision effectuée
                            </a>
                            <form method="POST"
                                  action="/drivelog/public/index.php?page=gestionnaire/maintenance/statut&id=<?= $v['id'] ?>">
                                <select name="statut" onchange="this.form.submit()">
                                    <?php foreach (['Disponible', 'En cours', 'En panne'] as $statut): ?>
                                        <option value="<?= $statut ?>"
                                            <?= $v['statut'] === $statut ? 'selected' : '' ?>>
                                            <?= $statut ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>