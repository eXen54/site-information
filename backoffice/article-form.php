<?php
session_start();
require_once '../includes/db.php';

// Vérifier la connexion
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$error = '';
$article = [
    'titre' => '',
    'slug' => '',
    'contenu' => '',
    'image_url' => '',
    'image_alt' => '',
    'meta_description' => ''
];

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

function resolveBackofficePreviewUrl(string $imageUrl): string
{
    if ($imageUrl === '') {
        return '';
    }
    if (strpos($imageUrl, 'http') === 0) {
        return $imageUrl;
    }

    $path = parse_url($imageUrl, PHP_URL_PATH) ?: $imageUrl;
    $path = str_replace('\\', '/', $path);

    if (preg_match('#(?:^|/)uploads/([^/]+)$#', $path, $matches)) {
        return '../uploads/' . $matches[1];
    }

    return '../' . ltrim($imageUrl, '/');
}

// Si un ID est fourni, récupérer l'article existant
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fetched = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fetched) {
            $article = $fetched;
        } else {
            $error = "Article introuvable.";
            $id = null;
        }
    } catch (PDOException $e) {
        $error = "Erreur SQL : " . $e->getMessage();
    }
}

// Traitement du formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $image_alt = trim($_POST['image_alt'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    // Validation basique
    if (empty($titre) || empty($slug) || empty($contenu)) {
        $error = "Les champs Titre, Slug et Contenu sont obligatoires.";
    } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
        $error = "Le slug contient des caractères non valides (uniquement a-z, 0-9 et -).";
    }

    $image_url_to_save = $article['image_url'];

    // Gestion de l'upload de l'image s'il n'y a pas d'erreur
    if (empty($error) && isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image_file'];
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $file_info = pathinfo($file['name']);
            $extension = strtolower($file_info['extension']);
            
            if (!in_array($extension, $allowed_extensions)) {
                $error = "Format d'image non autorisé (jpg, jpeg, png, webp).";
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                // 2 Mo max
                $error = "La taille de l'image dépasse 2 Mo.";
            } else {
                // Créer le dossier s'il n'existe pas
                $upload_dir = '../uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $new_filename = uniqid('img_') . '.' . $extension;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $image_url_to_save = 'uploads/' . $new_filename;
                    
                    // Si modification et ancienne image locale existe, on la supprime
                    if ($id && !empty($article['image_url']) && strpos($article['image_url'], 'http') !== 0) {
                        $old_filepath = getLocalUploadFilepath((string) $article['image_url']);
                        if ($old_filepath !== null && file_exists($old_filepath)) {
                            unlink($old_filepath);
                        }
                    }
                } else {
                    $error = "Erreur lors de l'enregistrement de l'image sur le serveur.";
                }
            }
        } else {
            $error = "Erreur lors du transfert de l'image (Code: " . $file['error'] . ")";
        }
    }

    // Ré-assigner les valeurs tapées pour ne pas les perdre
    $article['titre'] = $titre;
    $article['slug'] = $slug;
    $article['contenu'] = $contenu;
    $article['image_alt'] = $image_alt;
    $article['meta_description'] = $meta_description;

    // Insertion ou Mise à jour
    if (empty($error)) {
        try {
            if ($id) {
                // UPDATE
                $sql = "UPDATE articles SET 
                            titre = :titre, 
                            slug = :slug, 
                            contenu = :contenu, 
                            image_url = :image_url, 
                            image_alt = :image_alt, 
                            meta_description = :meta_description
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':titre' => $titre,
                    ':slug' => $slug,
                    ':contenu' => $contenu,
                    ':image_url' => $image_url_to_save,
                    ':image_alt' => $image_alt,
                    ':meta_description' => $meta_description,
                    ':id' => $id
                ]);
            } else {
                // INSERT
                $sql = "INSERT INTO articles (titre, slug, contenu, image_url, image_alt, meta_description) 
                        VALUES (:titre, :slug, :contenu, :image_url, :image_alt, :meta_description)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':titre' => $titre,
                    ':slug' => $slug,
                    ':contenu' => $contenu,
                    ':image_url' => $image_url_to_save,
                    ':image_alt' => $image_alt,
                    ':meta_description' => $meta_description
                ]);
            }
            
            header('Location: articles.php?msg=saved');
            exit;
            
        } catch (PDOException $e) {
            // Gestion erreur (ex: slug déjà existant)
            if ($e->getCode() == 23000) { // Violation de contrainte d'unicité
                $error = "Ce slug est déjà utilisé par un autre article.";
            } else {
                $error = "Erreur de base de données : " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Éditer' : 'Ajouter' ?> un Article - BackOffice</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/htz1hvla7mvoncok3m65vu9aqi79oxn50518tcs1vxlmzyu3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#contenu',
        menubar: false,
        plugins: 'lists link code',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | blockquote | code',
                block_formats: 'Paragraphe=p; Titre 1=h1; Titre 2=h2; Titre 3=h3; Titre 4=h4; Titre 5=h5; Titre 6=h6'
      });
    </script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --success-hover: #059669;
            --secondary: #6b7280;
            --secondary-hover: #4b5563;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #d1d5db;
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
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
            max-width: 900px; 
            margin: 0 auto; 
        }
        h1 { 
            margin-top: 0; 
            margin-bottom: 30px;
            font-size: 1.75rem;
            color: var(--text-main);
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
        }
        .form-group { margin-bottom: 24px; }
        .form-group label { 
            display: block; 
            font-weight: 500; 
            margin-bottom: 8px; 
            color: #374151;
            font-size: 0.95rem;
        }
        .form-group input[type="text"], .form-group input[type="file"], .form-group textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid var(--border-color); 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input[type="text"]:focus, .form-group input[type="file"]:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .form-group img { 
            max-width: 250px; 
            margin-top: 15px; 
            display: block; 
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .btn-submit { 
            background-color: var(--primary); 
            color: white; 
            border: none; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn-submit:hover { background-color: var(--primary-hover); }
        .btn-cancel { 
            background-color: var(--secondary); 
            color: white; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 6px; 
            display: inline-block; 
            margin-left: 12px; 
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn-cancel:hover { background-color: var(--secondary-hover); }
        .alert { 
            padding: 16px; 
            background-color: #fef2f2; 
            color: #991b1b; 
            border: 1px solid #fecaca; 
            border-radius: 6px; 
            margin-bottom: 24px; 
            font-size: 0.95rem;
            font-weight: 500;
        }
        .info { 
            font-size: 0.85rem; 
            color: var(--text-muted); 
            margin-top: 6px; 
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?= $id ? 'Éditer' : 'Ajouter' ?> un Article</h1>
    
    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre <span style="color:red">*</span></label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="slug">Slug (URL) <span style="color:red">*</span></label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($article['slug']) ?>" required>
            <div class="info">Laisser vide pour générer automatiquement à partir du titre. Caractères autorisés: a-z, 0-9 et tirets.</div>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu <span style="color:red">*</span></label>
            <textarea id="contenu" name="contenu" rows="15" style="width:100%"><?= htmlspecialchars($article['contenu']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="image_file">Image (Upload local)</label>
            <input type="file" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
            <?php if (!empty($article['image_url'])): ?>
                <div class="info">Image actuelle : <?= htmlspecialchars($article['image_url']) ?></div>
                <img src="<?= htmlspecialchars(resolveBackofficePreviewUrl((string) $article['image_url'])) ?>" alt="Aperçu">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="image_alt">Texte alternatif de l'image (Accessibilité)</label>
            <input type="text" id="image_alt" name="image_alt" value="<?= htmlspecialchars($article['image_alt']) ?>">
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description (SEO)</label>
            <input type="text" id="meta_description" name="meta_description" value="<?= htmlspecialchars($article['meta_description']) ?>" maxlength="160">
            <div class="info">Résumé de l'article (160 caractères max).</div>
        </div>

        <div>
            <button type="submit" class="btn-submit"><?= $id ? 'Mettre à jour' : 'Enregistrer' ?></button>
            <a href="articles.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<script>
// Génération automatique du slug à partir du titre
const slugify = (text) => {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
};

const titreInput = document.getElementById('titre');
const slugInput = document.getElementById('slug');
// Variable pour se souvenir si l'utilisateur a modifié manuellement le slug
let userEditedSlug = false;

slugInput.addEventListener('input', function() {
    userEditedSlug = true;
});

titreInput.addEventListener('input', function() {
    if (!userEditedSlug && "<?= $id ?>" == "") { // Génération auto uniquement pour une création ou si non touché
        slugInput.value = slugify(this.value);
    }
});

// Pour la création : Si champ sélectionné manuellement, on met quand même la génération si vide
titreInput.addEventListener('change', function() {
    if (slugInput.value.trim() === '') {
        slugInput.value = slugify(this.value);
        userEditedSlug = false; // Reset au cas où
    }
});
</script>

</body>
</html>
