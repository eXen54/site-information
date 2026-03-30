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
                        $old_filepath = '../' . ltrim($article['image_url'], '/');
                        if (file_exists($old_filepath)) {
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
    <title><?= $id ? 'Éditer' : 'Ajouter' ?> un Article - BackOffice</title>
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/htz1hvla7mvoncok3m65vu9aqi79oxn50518tcs1vxlmzyu3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#contenu',
        menubar: false,
        plugins: 'lists link code',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | blockquote | code',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3'
      });
    </script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto; }
        h1 { margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group img { max-width: 200px; margin-top: 10px; display: block; }
        .btn-submit { background-color: #28a745; color: white; border: none; padding: 10px 20px; text-decoration: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: #218838; }
        .btn-cancel { background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px; }
        .btn-cancel:hover { background-color: #5a6268; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
        .info { font-size: 13px; color: #666; margin-top: 5px; }
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
                <img src="../<?= htmlspecialchars($article['image_url']) ?>" alt="Aperçu">
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
