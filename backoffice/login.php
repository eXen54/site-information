<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Note : On compare directement les mots de passe ici car il est en clair dans init.sql (admin123)
    // Pour améliorer ça plus tard, on pourrait utiliser password_hash() et password_verify()
    if ($user && $password === $user['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header('Location: index.php'); // Redirection vers le tableau de bord
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion au BackOffice</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .login-box h1 { margin-top: 0; text-align: center; }
        .login-box input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 10px; background-color: #28a745; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .login-box button:hover { background-color: #218838; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Espace Sécurisé</h1>
    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="admin" required>
        
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" value="admin123" required>
        
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>