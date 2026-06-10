<?php
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>👥 Gestion des utilisateurs</h2>
        <a href="/drivelog/public/index.php?page=gestionnaire/user/creer" class="btn btn-primary">
            + Ajouter un utilisateur
        </a>
    </div>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (empty($utilisateurs)): ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge badge-en-cours">Admin</span>
                            <?php else: ?>
                                <span class="badge badge-disponible">Utilisateur</span>
                            <?php endif; ?>
                        </td>
                        <td style="display:flex; gap:6px;">
                            <a href="/drivelog/public/index.php?page=gestionnaire/user/modifier&id=<?= $u['id'] ?>"
                               class="btn btn-warning">Modifier</a>
                            <a href="/drivelog/public/index.php?page=gestionnaire/user/supprimer&id=<?= $u['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Formulaire ajout / modification -->
<?php if (isset($_GET['page']) &&
    ($_GET['page'] === 'gestionnaire/user/creer' ||
     $_GET['page'] === 'gestionnaire/user/modifier')): ?>

<div class="card" style="max-width:500px; margin:0 auto;">
    <h2><?= isset($user) ? '✏️ Modifier l\'utilisateur' : '➕ Ajouter un utilisateur' ?></h2>

    <form method="POST">

        <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom"
                value="<?= isset($user) ? htmlspecialchars($user['nom']) : '' ?>"
                placeholder="Ex: Jean Dupont" required>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email"
                value="<?= isset($user) ? htmlspecialchars($user['email']) : '' ?>"
                placeholder="Ex: jean@logistix.fr" required>
        </div>

        <?php if (!isset($user)): ?>
        <div class="form-group">
            <label>Mot de passe *</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Rôle *</label>
            <select name="role" required>
                <option value="utilisateur" <?= (isset($user) && $user['role'] === 'utilisateur') ? 'selected' : '' ?>>
                    Utilisateur
                </option>
                <option value="admin" <?= (isset($user) && $user['role'] === 'admin') ? 'selected' : '' ?>>
                    Admin
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= isset($user) ? 'Enregistrer les modifications' : 'Ajouter l\'utilisateur' ?>
        </button>
        <a href="/drivelog/public/index.php?page=gestionnaire/user" class="btn btn-secondary">Annuler</a>

    </form>
</div>

<?php endif; ?>