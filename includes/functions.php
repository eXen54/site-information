<?php

$appConfigPath = __DIR__ . '/app-config.php';
if (is_file($appConfigPath)) {
    require_once $appConfigPath;
}

function normalizeBasePath(string $basePath): string
{
    $basePath = trim(str_replace('\\', '/', $basePath));
    if ($basePath === '' || $basePath === '/') {
        return '';
    }

    return '/' . trim($basePath, '/');
}

function detectBasePathFromScript(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (preg_match('#^(.*?)/(?:frontoffice|backoffice|includes)(?:/.*)?$#', $scriptName, $matches)) {
        return normalizeBasePath($matches[1]);
    }

    return '';
}

function getBasePath(): string
{
    if (defined('APP_BASE_PATH')) {
        return normalizeBasePath((string) APP_BASE_PATH);
    }

    $envBasePath = getenv('APP_BASE_PATH');
    if (is_string($envBasePath) && trim($envBasePath) !== '') {
        return normalizeBasePath($envBasePath);
    }

    return detectBasePathFromScript();
}

function withBasePath(string $path, ?string $basePath = null): string
{
    $basePath = $basePath ?? getBasePath();
    $cleanPath = ltrim($path, '/');

    return ($basePath !== '' ? $basePath : '') . '/' . $cleanPath;
}

function resolveImageUrl(string $url, ?string $basePath = null): string
{
    $basePath = $basePath ?? getBasePath();
    $clean = trim($url);
    if ($clean === '') {
        return 'https://images.unsplash.com/photo-1528825871115-3581a5387919?auto=format&fit=crop&w=1600&q=80';
    }
    if (strpos($clean, 'http') === 0) {
        return $clean;
    }
    if (strpos($clean, '/') === 0) {
        if ($basePath !== '' && strpos($clean, $basePath . '/') === 0) {
            return $clean;
        }
        if ($basePath !== '' && strpos($clean, '/uploads/') === 0) {
            return $basePath . $clean;
        }
        return $clean;
    }

    return withBasePath($clean, $basePath);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getShortenedExcerpt(string $content, int $length = 150): string
{
    $text = strip_tags($content);
    $text = trim($text);
    if (mb_strlen($text) > $length) {
        return mb_substr($text, 0, $length) . '...';
    }

    return $text;
}

function calculateReadTime(string $content): int
{
    $wordCount = str_word_count(strip_tags($content));
    return max(1, (int) ceil($wordCount / 200));
}

function formatArticleDate(string $date): string
{
    try {
        $dateTime = new DateTime($date);
        if (class_exists('IntlDateFormatter')) {
            $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $formatted = $formatter->format($dateTime);
            if ($formatted !== false) {
                return $formatted;
            }
        }

        return $dateTime->format('d/m/Y');
    } catch (Exception $e) {
        return $date;
    }
}

function buildArticleBody(string $content): string
{
    $content = trim($content);
    if ($content === '') {
        return '<p>Contenu indisponible.</p>';
    }

    $containsHtml = $content !== strip_tags($content);
    if ($containsHtml) {
        $allowedTags = '<p><h1><h2><h3><h4><h5><h6><blockquote><ul><ol><li><strong><em><b><i><a><img><br>';
        return strip_tags($content, $allowedTags);
    }

    $paragraphs = preg_split('/\R{2,}/', $content) ?: [];
    $html = [];
    foreach ($paragraphs as $paragraph) {
        $paragraph = trim($paragraph);
        if ($paragraph === '') {
            continue;
        }
        $html[] = '<p>' . nl2br(escape($paragraph)) . '</p>';
    }

    return !empty($html) ? implode("\n", $html) : '<p>' . nl2br(escape($content)) . '</p>';
}

function getBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT id, titre, slug, contenu, image_url, image_alt, meta_description, date_creation FROM articles WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $result = $stmt->fetch();

    return $result ?: null;
}
