<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "Identifiant d'article invalide.";
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo "Article introuvable.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur BDD.";
    exit;
}

$title = $article['titre'];
$metaDescription = trim((string) $article['meta_description']) !== ''
    ? $article['meta_description']
    : mb_substr(trim(strip_tags((string) $article['contenu'])), 0, 155);        
$heroImage = trim((string) $article['image_url']) !== ''
    ? '../' . $article['image_url']
    : 'https://images.unsplash.com/photo-1528825871115-3581a5387919?auto=format&fit=crop&w=1600&q=80';
// Gestion du chemin de l'image : si c'est une URL http, on la garde, sinon on préfixe avec ../ pour sortir du dossier backoffice
if (trim((string) $article['image_url']) !== '') {
    $heroImage = strpos($article['image_url'], 'http') === 0 ? $article['image_url'] : '../' . $article['image_url'];
}

$heroAlt = trim((string) $article['image_alt']) !== ''
    ? $article['image_alt']
    : 'Illustration de l\'article';

// Petite fonction simple pour bypasser buildArticleBody s'il y a un souci de chemin relatif
$articleHtml = $article['contenu']; 
// Normalement on utilise buildArticleBody mais on peut le faire en direct ici
if (function_exists('buildArticleBody')) {
    $articleHtml = buildArticleBody((string) $article['contenu']);
}

$publishedAt = function_exists('formatArticleDate') ? formatArticleDate((string) $article['date_creation']) : $article['date_creation'];

?>
<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>     
    <title>Prévisualisation : <?= escape($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>       
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;700;800;900&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap"/>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;700;800;900&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#002147',
                        surface: '#faf9fd',
                        onSurface: '#1a1b1e',
                        muted: '#44474e',
                        line: '#e3e2e6'
                    },
                    fontFamily: {
                        headline: ['Work Sans'],
                        body: ['Newsreader']
                    }
                }
            }
        };
    </script>
    <style>
        body { min-height: 100dvh; }
        .article-content p { margin-bottom: 1.5rem; line-height: 1.8; font-size: 1.125rem; }
        .article-content h1, .article-content h2, .article-content h3 { font-family: 'Work Sans', sans-serif; font-weight: 800; letter-spacing: -0.02em; color: #002147; margin-top: 2.5rem; margin-bottom: 1rem; }
        .article-content h1 { font-size: 2rem; }
        .article-content h2 { font-size: 1.6rem; }
        .article-content blockquote { margin: 2rem 0; padding: 1rem 1.5rem; border-left: 4px solid #002147; background: #f4f3f7; font-style: italic; }
        .article-content ul, .article-content ol { margin: 1rem 0 1.5rem 1.5rem; }
        .article-content a { color: #002147; text-decoration: underline; }
        
        .preview-bar {
            background-color: #f59e0b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Work Sans', sans-serif;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .preview-bar a {
            background: #b45309;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .preview-bar a:hover { background: #92400e; }
    </style>
</head>
<body class="bg-surface text-onSurface font-body selection:bg-blue-100">

<!-- Barre de prévisualisation BackOffice -->
<div class="preview-bar">
    <div><strong>Mode Prévisualisation</strong> - Cet article s'affichera ainsi sur le site public.</div>
    <div>
        <a href="article-form.php?id=<?= $id ?>">Modifier l'article</a>
        <a href="articles.php" style="margin-left: 10px; background: #1f2937;">Retour BackOffice</a>
    </div>
</div>

<main class="max-w-6xl mx-auto px-4 md:px-8 pt-10 md:pt-14 pb-16">
    <header class="max-w-4xl mx-auto mb-10 text-center md:text-left">
        <div class="text-xs uppercase tracking-[0.2em] text-muted font-semibold mb-3">
            Article #<?= (int) $article['id'] ?> • <?= escape($publishedAt) ?>
        </div>
        <h1 class="font-headline font-black text-4xl md:text-6xl leading-tight tracking-tight text-primary mb-6">
            <?= escape($title) ?>
        </h1>
        <p class="text-sm text-muted">
            URL lisible: /<?= escape($article['slug']) ?>
        </p>
    </header>

    <section class="max-w-5xl mx-auto mb-12">
        <div class="aspect-[16/9] overflow-hidden rounded-md bg-gray-100">      
            <img class="w-full h-full object-cover"
                 src="<?= escape($heroImage) ?>"
                 alt="<?= escape($heroAlt) ?>"
                 width="1600"
                 height="900"
                 loading="eager"
                 decoding="async"/>
        </div>
    </section>

    <article class="max-w-4xl mx-auto article-content text-onSurface">
        <?= $articleHtml ?>
    </article>
</main>

<footer class="border-t border-line bg-[#f4f3f7]">
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-8 text-sm text-muted">        
        © <?= date('Y') ?> Info Iran - Aperçu BackOffice.
    </div>
</footer>
</body>
</html>