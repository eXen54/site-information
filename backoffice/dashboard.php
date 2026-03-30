<?php
session_start();
require_once '../includes/db.php';

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Récupérer le nombre total d'articles
$total_articles = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
    $total_articles = $stmt->fetchColumn();
} catch (PDOException $e) {
    $error = "Erreur de récupération des statistiques : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BackOffice</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: bold; }
        .nav-links a:hover { text-decoration: underline; color: #0056b3; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        .btn-logout:hover { background-color: #c82333; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
        .stat-card { background: #007bff; color: white; padding: 20px; border-radius: 5px; text-align: center; width: 200px; display: inline-block; margin-top: 20px;}
        .stat-card h3 { margin: 0 0 10px 0; font-size: 1.2em; }
        .stat-card p { margin: 0; font-size: 2em; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Dashboard</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="articles.php">Gérer les Articles</a>
        </div>
        <div>
            <span>Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></strong></span>
            <a href="logout.php" class="btn-logout" style="margin-left: 15px;">Se déconnecter</a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="stat-card">
        <h3>Total des Articles</h3>
        <p><?= $total_articles ?></p>
    </div>
</div>

</body>
</html>
