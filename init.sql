CREATE DATABASE IF NOT EXISTS info_iran;
USE info_iran;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
);

CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    contenu TEXT NOT NULL,
    image_url VARCHAR(255),
    image_alt VARCHAR(150),
    meta_description VARCHAR(160),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO utilisateurs (username, password, role)
SELECT 'admin', 'admin123', 'admin'
WHERE NOT EXISTS (
    SELECT 1 FROM utilisateurs WHERE username = 'admin'
);

INSERT INTO articles (titre, slug, contenu, image_alt, meta_description)
SELECT 
    'Les origines du conflit', 
    'origines-conflit', 
    'Contenu de test sur les origines.', 
    'Illustration conflit', 
    'Une brève description pour Google.'
WHERE NOT EXISTS (
    SELECT 1 FROM articles WHERE slug = 'origines-conflit'
);