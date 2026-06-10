<?php
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>🚗 Gestion du parc</h2>
        <a href="/drivelog/public/index.php?page=gestionnaire/parc/creer" class="btn btn-primary">
            + Ajouter un véhicule
        </a>
    </div>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (empty($vehicules)): ?>
        <p>Aucun véhicule dans le parc.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>Type</th>
                    <th>Date achat</th>
                    <th>KM actuel</th>
                    <th>Entretien</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($v['modele']) ?></td>
                        <td><?= htmlspecialchars($v['type']) ?></td>
                        <td><?= htmlspecialchars($v['date_achat']) ?></td>
                        <td><?= number_format($v['km_actuel'], 0, ',', ' ') ?> km</td>
                        <td>
                            <?php
                            $ecart = $v['km_actuel'] - $v['km_dernier_entretien'];
                            if ($ecart > 20000): ?>
                                <span class="badge badge-en-panne">⚠️ Révision requise</span>
                            <?php else: ?>
                                <span class="badge badge-disponible">✓ OK</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $badgeClass = match($v['statut']) {
                                'Disponible' => 'badge-disponible',
                                'En cours'   => 'badge-en-cours',
                                'En panne'   => 'badge-en-panne',
                                default      => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($v['statut']) ?>
                            </span>
                        </td>
                        <td style="display:flex; gap:6px;">
                            <a href="/drivelog/public/index.php?page=gestionnaire/parc/modifier&id=<?= $v['id'] ?>"
                               class="btn btn-warning">Modifier</a>
                            <a href="/drivelog/public/index.php?page=gestionnaire/parc/supprimer&id=<?= $v['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Supprimer ce véhicule ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Formulaire ajout / modification -->
<?php if (isset($_GET['page']) && 
    ($_GET['page'] === 'gestionnaire/parc/creer' || 
     $_GET['page'] === 'gestionnaire/parc/modifier')): ?>

<div class="card" style="max-width:600px; margin:0 auto;">
    <h2><?= isset($vehicule) ? '✏️ Modifier le véhicule' : '➕ Ajouter un véhicule' ?></h2>

    <form method="POST">

        <div class="form-group">
            <label>Immatriculation *</label>
            <input type="text" name="immatriculation"
                value="<?= isset($vehicule) ? htmlspecialchars($vehicule['immatriculation']) : '' ?>"
                placeholder="Ex: AB-123-CD" required>
        </div>

        <div class="form-group">
            <label>Modèle *</label>
            <input type="text" name="modele"
                value="<?= isset($vehicule) ? htmlspecialchars($vehicule['modele']) : '' ?>"
                placeholder="Ex: Renault Clio" required>
        </div>

        <div class="form-group">
            <label>Type *</label>
            <select name="type" required>
                <option value="">-- Choisir --</option>
                <?php foreach (['citadine', 'utilitaire', 'electrique'] as $type): ?>
                    <option value="<?= $type ?>"
                        <?= (isset($vehicule) && $vehicule['type'] === $type) ? 'selected' : '' ?>>
                        <?= ucfirst($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Date d'achat *</label>
            <input type="date" name="date_achat"
                value="<?= isset($vehicule) ? htmlspecialchars($vehicule['date_achat']) : '' ?>"
                required>
        </div>

        <div class="form-group">
            <label>Kilométrage actuel *</label>
            <input type="number" name="km_actuel" min="0"
                value="<?= isset($vehicule) ? $vehicule['km_actuel'] : '' ?>"
                placeholder="Ex: 45000" required>
        </div>

        <?php if (isset($vehicule)): ?>
        <div class="form-group">
            <label>Statut *</label>
            <select name="statut" required>
                <?php foreach (['Disponible', 'En cours', 'En panne'] as $statut): ?>
                    <option value="<?= $statut ?>"
                        <?= $vehicule['statut'] === $statut ? 'selected' : '' ?>>
                        <?= $statut ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">
            <?= isset($vehicule) ? 'Enregistrer les modifications' : 'Ajouter le véhicule' ?>
        </button>
        <a href="/drivelog/public/index.php?page=gestionnaire/parc" class="btn btn-secondary">Annuler</a>

    </form>
</div>

<?php endif; ?>