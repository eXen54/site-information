create database info_iran;
use info_iran;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL, -- Utilisé pour l'URL Rewriting
    contenu TEXT NOT NULL,
    image_url VARCHAR(255),
    image_alt VARCHAR(150),     -- Pour le SEO
    meta_description VARCHAR(160), -- Pour le SEO
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion d'un utilisateur par défaut pour le BackOffice (mot de passe : "admin123" hashé)
-- Note: Dans un vrai projet, utilisez password_hash() en PHP. Ici on met un exemple simple.
INSERT INTO utilisateurs (username, password) 
VALUES ('admin', 'admin123');

-- Insertion d'un article de test
INSERT INTO articles (titre, slug, contenu, image_alt, meta_description) 
VALUES ('Les origines du conflit', 'origines-conflit', 'Contenu de test sur les origines.', 'Illustration conflit', 'Une brève description pour Google.');