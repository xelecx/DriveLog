<?php
// Si déjà connecté, pas besoin d'afficher cette page
if (isset($_SESSION['user'])) {
    header("Location: /drivelog/public/index.php?page=collaborateur/client");
    exit();
}
?>

<style>
    body {
        background-color: #1a1a2e;
    }

    .login-container {
        max-width: 420px;
        margin: 80px auto;
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }

    .login-container h1 {
        text-align: center;
        color: #1a1a2e;
        margin-bottom: 8px;
        font-size: 26px;
    }

    .login-container p.subtitle {
        text-align: center;
        color: #999;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .login-container .btn-primary {
        width: 100%;
        padding: 12px;
        font-size: 15px;
        margin-top: 8px;
    }
</style>

<div class="login-container">
    <h1>🚗 DriveLog</h1>
    <p class="subtitle">Gestion de flotte – Logistix</p>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" action="/drivelog/public/index.php?page=login">

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="exemple@logistix.fr"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>

    </form>
</div>