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
        header('Location: articles.php'); // Redirection vers la gestion des articles
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion au BackOffice</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --border-color: #d1d5db;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background-color: var(--bg-color); 
            margin: 0; 
            -webkit-font-smoothing: antialiased;
        }
        .login-box { 
            background: var(--card-bg); 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
            width: 100%; 
            max-width: 400px; 
            box-sizing: border-box;
        }
        .login-box h1 { 
            margin-top: 0; 
            text-align: center; 
            color: var(--text-main);
            font-size: 1.5rem;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .login-box label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            color: #374151;
            font-weight: 500;
        }
        .login-box input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 20px; 
            border: 1px solid var(--border-color); 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .login-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .login-box button { 
            width: 100%; 
            padding: 12px; 
            background-color: var(--primary); 
            border: none; 
            color: white; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: 600;
            transition: background-color 0.2s, transform 0.1s;
        }
        .login-box button:hover { background-color: var(--primary-hover); }
        .login-box button:active { transform: translateY(1px); }
        .error { 
            color: #b91c1c; 
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 10px;
            border-radius: 6px;
            text-align: center; 
            margin-bottom: 20px; 
            font-size: 0.9rem;
            font-weight: 500;
        }
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