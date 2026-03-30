<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$articleSlug = trim($_GET['slug'] ?? '');
if (!$articleSlug || !preg_match('/^[a-z0-9-]+$/', $articleSlug)) {
    http_response_code(400);
    echo 'Identifiant d\'article invalide.';
    exit;
}

$article = getBySlug($pdo, $articleSlug);

if (!$article) {
    http_response_code(404);
    echo 'Article introuvable.';
    exit;
}

$title = $article['titre'];
$metaDescription = trim((string) $article['meta_description']) !== ''
    ? $article['meta_description']
    : mb_substr(trim(strip_tags((string) $article['contenu'])), 0, 155);
$heroImage = 'https://images.unsplash.com/photo-1528825871115-3581a5387919?auto=format&fit=crop&w=1600&q=80';
if (trim((string) $article['image_url']) !== '') {
    $heroImage = strpos($article['image_url'], 'http') === 0 ? $article['image_url'] : '/' . ltrim($article['image_url'], '/');
}
$heroAlt = trim((string) $article['image_alt']) !== ''
    ? $article['image_alt']
    : 'Illustration de l\'article';
$articleHtml = buildArticleBody((string) $article['contenu']);
$publishedAt = formatArticleDate((string) $article['date_creation']);

$protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$canonicalUrl = $protocol . '://' . $host . '/articles/' . $article['slug'];
?>
<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= escape($title) ?> | Site d'informations</title>
    <meta name="description" content="<?= escape($metaDescription) ?>"/>
    <meta property="og:title" content="<?= escape($title) ?>"/>
    <meta property="og:description" content="<?= escape($metaDescription) ?>"/>
    <meta property="og:image" content="<?= escape($heroImage) ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="article:published_time" content="<?= date('c', strtotime($article['date_creation'])) ?>"/>
    <link rel="canonical" href="<?= $canonicalUrl ?>"/>
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
        body {
            min-height: 100dvh;
        }

        .article-content p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            font-size: 1.125rem;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3 {
            font-family: 'Work Sans', sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #002147;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .article-content h1 {
            font-size: 2rem;
        }

        .article-content h2 {
            font-size: 1.6rem;
        }

        .article-content blockquote {
            margin: 2rem 0;
            padding: 1rem 1.5rem;
            border-left: 4px solid #002147;
            background: #f4f3f7;
            font-style: italic;
        }

        .article-content ul,
        .article-content ol {
            margin: 1rem 0 1.5rem 1.5rem;
        }

        .article-content a {
            color: #002147;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-surface text-onSurface font-body selection:bg-blue-100">
<header class="border-b border-line bg-white/90 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
        <a class="text-2xl md:text-3xl font-black tracking-tight text-primary font-headline" href="/">Info Iran</a>
        <a class="text-sm font-semibold text-white bg-primary px-4 py-2 rounded shadow-sm hover:bg-opacity-90 transition-colors" href="/backoffice/login.php">Connexion</a>
    </div>
</header>

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
        © <?= date('Y') ?> Info Iran - FrontOffice.
    </div>
</footer>
</body>
</html>
