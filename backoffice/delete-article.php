<?php
session_start();
require_once '../includes/db.php';

function getLocalUploadFilepath(string $imageUrl): ?string
{
    if ($imageUrl === '' || strpos($imageUrl, 'http') === 0) {
        return null;
    }

    $path = parse_url($imageUrl, PHP_URL_PATH) ?: $imageUrl;
    $path = str_replace('\\', '/', $path);

    if (preg_match('#(?:^|/)uploads/([^/]+)$#', $path, $matches)) {
        return __DIR__ . '/../uploads/' . $matches[1];
    }

    return null;
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // 1. Récupérer l'article pour avoir l'URL de l'image
        $stmt = $pdo->prepare("SELECT image_url FROM articles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            // 2. Si une image existe localement, la supprimer
            $image_url = $article['image_url'];
            // On vérifie si l'URL correspond à notre dossier uploads (ex: uploads/image.jpg ou /uploads/image.jpg)
            if (!empty($image_url) && strpos($image_url, 'http') !== 0) {
                $filepath = getLocalUploadFilepath((string) $image_url);
                if ($filepath !== null && file_exists($filepath)) {
                    unlink($filepath);
                }
            }

            // 3. Supprimer l'article de la BDD
            $delStmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
            $delStmt->execute([':id' => $id]);
        }
        
    } catch (PDOException $e) {
        // En vrai production, on journalise l'erreur
        // error_log($e->getMessage());
    }
}

// Rediriger vers la liste des articles
header('Location: articles.php?msg=deleted');
exit;
