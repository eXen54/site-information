<?php
session_start();

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - BackOffice</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        .btn-logout:hover { background-color: #c82333; }
        .btn-add { background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px; }
        .btn-add:hover { background-color: #0069d9; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Tableau de Bord</h1>
        <div>
            <span>Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
            <a href="logout.php" class="btn-logout" style="margin-left: 15px;">Se déconnecter</a>
        </div>
    </div>

    <h2>Gestion des Articles</h2>
    <a href="add_article.php" class="btn-add">+ Ajouter un Article</a>

    <!-- Le tableau listant les articles viendra ici -->
    <p><em>Aucun article à afficher pour le moment. Le CRUD est en cours de création.</em></p>
</div>

</body>
</html>