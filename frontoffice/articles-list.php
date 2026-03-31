<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Récupérer tous les articles triés par date décroissante
$stmt = $pdo->prepare('SELECT id, titre, slug, contenu, image_url, image_alt, meta_description, date_creation FROM articles ORDER BY date_creation DESC');
$stmt->execute();
$articles = $stmt->fetchAll();

$featured = !empty($articles) ? array_shift($articles) : null;

$protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = getBasePath();
$listUrl = $protocol . '://' . $host . withBasePath('guerre-iran-actualites', $basePath);
?>
<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Guerre Iran Actualites | Dossiers Et Analyses</title>
    <meta name="description" content="Guerre Iran actualites: suivez les dernieres analyses, le contexte geopolitique et les impacts humanitaires en temps reel."/>
    <meta name="keywords" content="guerre iran actualites, conflit iran, actualites moyen orient, analyse geopolitique iran"/>
    <link rel="canonical" href="<?= escape($listUrl) ?>"/>
    <meta property="og:title" content="Guerre Iran Actualites | Dossiers Et Analyses"/>
    <meta property="og:description" content="Suivez la guerre en Iran avec des actualites verifiees, des analyses et des dossiers de fond."/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?= escape($listUrl) ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="Guerre Iran Actualites | Dossiers Et Analyses"/>
    <meta name="twitter:description" content="Actualites et analyses sur la guerre en Iran."/>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;700;800;900&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-surface text-onSurface font-body selection:bg-blue-100">

<!-- Header -->
<header class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-line">
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
        <a class="text-2xl md:text-3xl font-black tracking-tighter text-primary font-headline" href="<?= escape(withBasePath('guerre-iran-actualites', $basePath)) ?>">Info Iran</a>
        <nav class="hidden md:flex gap-8">
            <a class="text-sm font-semibold text-primary hover:text-muted transition-colors" href="<?= escape(withBasePath('guerre-iran-actualites', $basePath)) ?>">Actualités</a>
        </nav>
        <a class="text-sm font-semibold text-white bg-primary px-4 py-2 rounded shadow-sm hover:bg-opacity-90 transition-colors" href="<?= escape(withBasePath('backoffice/login.php', $basePath)) ?>">Connexion</a>
    </div>
</header>

<main class="pt-24 pb-16">
    <!-- Search & Header Section -->
    <section class="max-w-6xl mx-auto px-4 md:px-8 mb-16">
        <div class="mb-10">
            <h1 class="font-headline font-black text-5xl md:text-7xl text-primary tracking-tighter leading-none mb-4">
                La guerre en Iran, actualites
            </h1>
            <p class="text-lg text-muted font-body">
                Couverture complète de la situation géopolitique, humanitaire et économique en Iran.
            </p>
        </div>
        <div class="relative max-w-md">
            <span class="absolute left-0 top-3 text-muted">
                <span class="material-symbols-outlined text-sm">search</span>
            </span>
            <input class="w-full bg-transparent border-none border-b border-line focus:border-primary focus:ring-0 py-3 pl-8 font-headline text-lg transition-all outline-none" placeholder="Rechercher..." type="text"/>
        </div>
    </section>

    <?php if ($featured): ?>
    <!-- Featured Article -->
    <section class="max-w-6xl mx-auto px-4 md:px-8 mb-16">
        <div class="group">
            <div class="overflow-hidden rounded-t-md md:rounded-t-lg">
                <img class="w-full aspect-[16/9] object-cover hover:scale-105 transition-transform duration-500"
                     src="<?= escape(resolveImageUrl($featured['image_url'], $basePath)) ?>"
                     alt="<?= escape($featured['image_alt'] ?? 'Article') ?>" loading="lazy"/>
            </div>
            <div class="bg-white p-8 md:p-10 shadow-xl shadow-primary/10 rounded-b-md md:rounded-b-lg border border-t-0 border-line/60">
                <div class="flex items-center gap-3 mb-4">
                    <span class="font-headline text-xs text-muted uppercase tracking-widest font-semibold">À la Une</span>
                    <span class="font-headline text-xs text-muted tracking-widest"><?= (int)calculateReadTime($featured['contenu']) ?> MIN</span>
                </div>
                <h2 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter leading-tight mb-4">
                    <?= escape($featured['titre']) ?>
                </h2>
                <p class="font-body text-muted leading-relaxed mb-6">
                    <?= escape(getShortenedExcerpt($featured['contenu'], 180)) ?>
                </p>
                <a class="inline-flex items-center gap-2 group/link" href="<?= escape(withBasePath('articles/' . $featured['slug'], $basePath)) ?>">
                    <span class="font-headline font-bold text-sm uppercase tracking-widest text-primary">Lire l'article</span>
                    <span class="material-symbols-outlined text-primary group-hover/link:translate-x-1 transition-transform text-sm">arrow_right_alt</span>
                </a>
            </div>
        </div>
    </section>

    <?php endif; ?>

    <!-- Articles Grid -->
    <section class="max-w-6xl mx-auto px-4 md:px-8">
        <?php if (!empty($articles)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <?php foreach ($articles as $article): ?>
                <article class="flex flex-col gap-6 group">
                    <div class="overflow-hidden rounded-md">
                        <img class="w-full aspect-square object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all duration-500" 
                                src="<?= escape(resolveImageUrl($article['image_url'], $basePath)) ?>" 
                             alt="<?= escape($article['image_alt'] ?? 'Article') ?>" loading="lazy"/>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <span class="font-headline font-bold uppercase tracking-widest text-xs text-muted"><?= (int)calculateReadTime($article['contenu']) ?> MIN</span>
                            <span class="font-headline text-xs text-muted tracking-widest">
                                <?= date('d M Y', strtotime($article['date_creation'])) ?>
                            </span>
                        </div>
                        <h3 class="font-headline font-bold text-xl text-primary tracking-tight leading-tight">
                            <?= escape($article['titre']) ?>
                        </h3>
                        <p class="font-body text-muted leading-relaxed line-clamp-3">
                            <?= escape(getShortenedExcerpt($article['contenu'], 130)) ?>
                        </p>
                        <a class="inline-flex items-center gap-2 group/link mt-auto" href="<?= escape(withBasePath('articles/' . $article['slug'], $basePath)) ?>">
                            <span class="font-headline font-bold text-xs uppercase tracking-widest text-primary">Lire</span>
                            <span class="material-symbols-outlined text-primary group-hover/link:translate-x-1 transition-transform text-xs">arrow_right_alt</span>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="py-24 text-center">
                <p class="text-lg text-muted">Aucun article disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Footer -->
<footer class="border-t border-line bg-surface mt-24">
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-12 flex flex-col md:flex-row justify-between items-center">
        <div class="mb-8 md:mb-0">
            <h3 class="font-headline font-black text-primary text-2xl tracking-tighter">Info Iran</h3>
            <p class="font-body italic text-muted mt-2">© 2026 Couverture Actualités Iran - Tous droits réservés</p>
        </div>
        <nav class="flex gap-8 items-center">
            <a class="text-muted font-body hover:text-primary transition-colors text-sm" href="#about">À propos</a>
            <a class="text-muted font-body hover:text-primary transition-colors text-sm" href="#privacy">Confidentialité</a>
            <a class="text-muted font-body hover:text-primary transition-colors text-sm" href="<?= escape(withBasePath('guerre-iran-actualites', $basePath)) ?>">Actualités</a>
        </nav>
    </div>
</footer>

</body>
</html>
