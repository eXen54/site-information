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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - BackOffice</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --success-hover: #059669;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --warning: #f59e0b;
            --warning-hover: #d97706;
            --info: #3b82f6;
            --info-hover: #2563eb;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color); 
            color: var(--text-main);
            margin: 0; 
            padding: 40px 20px; 
            -webkit-font-smoothing: antialiased;
        }
        .container { 
            background: var(--card-bg); 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
            max-width: 1100px; 
            margin: 0 auto; 
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid var(--border-color); 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--text-main);
            font-weight: 600;
        }
        .nav-links a { 
            margin-right: 15px; 
            text-decoration: none; 
            color: var(--primary); 
            font-weight: 500; 
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--primary-hover); }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.1s;
            border: none;
            cursor: pointer;
        }
        .btn:active { transform: translateY(1px); }
        .btn-logout { background-color: var(--danger); color: white; }
        .btn-logout:hover { background-color: var(--danger-hover); }
        .btn-add { background-color: var(--success); color: white; margin-bottom: 20px; font-size: 1rem; padding: 10px 20px;}
        .btn-add:hover { background-color: var(--success-hover); }
        
        .btn-view { background-color: var(--info); color: white; padding: 6px 12px;}
        .btn-view:hover { background-color: var(--info-hover); }
        .btn-edit { background-color: #fce83a; color: #92400e; padding: 6px 12px; font-weight: 600; }
        .btn-edit:hover { background-color: #fde047; }
        .btn-delete { background-color: var(--danger); color: white; padding: 6px 12px; }
        .btn-delete:hover { background-color: var(--danger-hover); }

        .table-container {
            overflow-x: auto;
        }
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 10px; 
        }
        th, td { 
            padding: 12px 16px; 
            text-align: left; 
            border-bottom: 1px solid var(--border-color);
        }
        th { 
            background-color: #f9fafb; 
            font-weight: 600; 
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f9fafb; }
        .alert, .success { 
            padding: 12px 16px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 0.95rem;
            font-weight: 500;
        }
        .alert { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        
        .actions-cell {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Gestion des Articles</h1>
        <div class="user-menu">
            <span>Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></strong></span>
            <a href="logout.php" class="btn btn-logout">Se déconnecter</a>
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

    <a href="article-form.php" class="btn btn-add">+ Ajouter un Article</a>

    <div class="table-container">
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
                            <td class="actions-cell">
                                <!-- Lien vers la page de prévisualisation du BackOffice -->
                                <a href="preview.php?id=<?= urlencode($article['id']) ?>" target="_blank" class="btn btn-view">Voir</a>
                                <a href="article-form.php?id=<?= urlencode($article['id']) ?>" class="btn btn-edit">Modifier</a>
                                <a href="delete-article.php?id=<?= urlencode($article['id']) ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">Aucun article trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
