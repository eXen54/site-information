<?php
$host = 'db'; // Utiliser le nom du service Docker au lieu de 'localhost'
$dbname = 'info_iran';
$user = 'neks';
$pass = 'neks'; // Mot de passe défini dans docker-compose.yml
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$max_attempts = 10;
$attempt = 0;
$pdo = null;

do {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, $options);
    } catch (PDOException $e) {
        $attempt++;
        if ($attempt >= $max_attempts) {
            die("Erreur de connexion à la base de données après $max_attempts tentatives: " . $e->getMessage());
        }
        // Attendre 5 secondes avant de réessayer
        sleep(5);
    }
} while ($pdo === null);
?>