<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveLog – Gestion de Flotte</title>
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        /* NAVBAR */
        nav {
            background-color: #1a1a2e;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            color: #e94560;
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        nav ul li a:hover {
            background-color: #e94560;
        }

        /* CONTENU PRINCIPAL */
        main {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* MESSAGES */
        .alert-erreur {
            background-color: #ffe0e0;
            border-left: 4px solid #e94560;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #c0392b;
        }

        .alert-succes {
            background-color: #e0ffe0;
            border-left: 4px solid #27ae60;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #27ae60;
        }

        /* TABLEAUX */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        table th {
            background-color: #1a1a2e;
            color: #fff;
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        table tr:hover {
            background-color: #fafafa;
        }

        /* BOUTONS */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s;
        }

        .btn:hover { opacity: 0.85; }

        .btn-primary  { background-color: #e94560; color: #fff; }
        .btn-success  { background-color: #27ae60; color: #fff; }
        .btn-warning  { background-color: #f39c12; color: #fff; }
        .btn-danger   { background-color: #c0392b; color: #fff; }
        .btn-secondary { background-color: #95a5a6; color: #fff; }

        /* FORMULAIRES */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #e94560;
            outline: none;
        }

        /* CARTE */
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #1a1a2e;
            font-size: 20px;
        }

        /* BADGE STATUT */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-disponible  { background-color: #d5f5e3; color: #27ae60; }
        .badge-en-cours    { background-color: #fef9e7; color: #f39c12; }
        .badge-en-panne    { background-color: #fadbd8; color: #c0392b; }
    </style>
</head>
<body>

<nav>
    <a href="/drivelog/public/index.php" class="logo">🚗 DriveLog</a>

    <?php if (isset($_SESSION['user'])): ?>
        <ul>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <!-- Menu gestionnaire -->
                <li><a href="/drivelog/public/index.php?page=gestionnaire/parc">Parc</a></li>
                <li><a href="/drivelog/public/index.php?page=gestionnaire/maintenance">Maintenance</a></li>
                <li><a href="/drivelog/public/index.php?page=reservation/liste">Réservations</a></li>
                <li><a href="/drivelog/public/index.php?page=gestionnaire/stats">Statistiques</a></li>
                <li><a href="/drivelog/public/index.php?page=gestionnaire/user">Utilisateurs</a></li>
            <?php else: ?>
                <!-- Menu collaborateur -->
                <li><a href="/drivelog/public/index.php?page=collaborateur/client">Véhicules</a></li>
                <li><a href="/drivelog/public/index.php?page=reservation/nouvelle">Réserver</a></li>
                <li><a href="/drivelog/public/index.php?page=collaborateur/trajet">Mes trajets</a></li>
            <?php endif; ?>

            <!-- Toujours visible -->
            <li><a href="/drivelog/public/index.php?page=logout">
                👋 Déconnexion (<?= htmlspecialchars($_SESSION['user']['nom']) ?>)
            </a></li>
        </ul>
    <?php endif; ?>
</nav>

<main>