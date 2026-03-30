<?php
session_start();
require_once '../includes/db.php';

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Récupérer tous les articles
try {
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY date_creation DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur de récupération des articles : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Articles - BackOffice</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: bold; }
        .nav-links a:hover { text-decoration: underline; color: #0056b3; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        .btn-logout:hover { background-color: #c82333; }
        .btn-add { background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px; }
        .btn-add:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-view { background-color: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px; margin-right: 5px; }
        .btn-view:hover { background-color: #138496; }
        .btn-edit { background-color: #ffc107; color: black; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px; margin-right: 5px; }
        .btn-edit:hover { background-color: #e0a800; }
        .btn-delete { background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .btn-delete:hover { background-color: #c82333; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
        .success { padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Gestion des Articles</h1>
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

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="success">Article supprimé avec succès.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
        <div class="success">Article enregistré avec succès.</div>
    <?php endif; ?>

    <a href="article-form.php" class="btn-add">+ Ajouter un Article</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Slug</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars($article['id']) ?></td>
                        <td><?= htmlspecialchars($article['titre']) ?></td>
                        <td><?= htmlspecialchars($article['slug']) ?></td>
                        <td><?= htmlspecialchars($article['date_creation']) ?></td>
                        <td>
                            <!-- Lien vers FrontOffice, assumant le RewriteRule ou l'accès direct via ID -->
                            <a href="../articles/<?= urlencode($article['id']) ?>" target="_blank" class="btn-view">Voir</a>
                            <a href="article-form.php?id=<?= urlencode($article['id']) ?>" class="btn-edit">Modifier</a>
                            <a href="delete-article.php?id=<?= urlencode($article['id']) ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Aucun article trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
