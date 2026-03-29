<?php
$host = 'db'; // Correspond au nom du service de la base de données dans docker-compose.yml
$dbname = 'info_iran';
$user = 'neks';
$pass = 'neks';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Activer le rapport d'erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>