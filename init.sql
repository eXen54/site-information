CREATE DATABASE IF NOT EXISTS info_iran;
USE info_iran;

DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    contenu TEXT NOT NULL,
    image_url VARCHAR(255),
    image_alt VARCHAR(150),
    meta_description VARCHAR(160),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (slug),
    INDEX (date_creation)
);

-- Test users
INSERT INTO utilisateurs (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('journalist', 'journalist123', 'editor');

-- Test articles with rich content
INSERT INTO articles (titre, slug, contenu, image_url, image_alt, meta_description) VALUES
(
    'Les origines du conflit en Iran',
    'origines-conflit',
    '<h1>Les origines du conflit</h1><p>Le conflit en Iran a des racines historiques complexes remontant à plusieurs siècles. Cet article examine les évènements clés qui ont mené à la situation actuelle.</p><h2>Contexte historique</h2><p>Au cours du XXème siècle, l\'Iran a connu d\'importantes transformations politiques et sociales qui ont façonné le paysage contemporain.</p><blockquote>La compréhension du passé est essentielle pour anticiper l\'avenir.</blockquote><h2>Facteurs géopolitiques</h2><p>Les puissances régionales et internationales ont joué un rôle crucial dans l\'évolution du conflit.</p>',
    'https://images.unsplash.com/photo-1528825871115-3581a5387919?auto=format&fit=crop&w=1600&q=80',
    'Manifestation iranienne pour les droits civiques',
    'Découvrez les origines historiques du conflit en Iran et ses facteurs géopolitiques.'
),
(
    'Situation humanitaire et civils',
    'situation-humanitaire',
    '<h1>La crise humanitaire en Iran</h1><p>Des millions de personnes sont affectées par les tensions actuelles. Cette analyse se concentre sur l\'impact humanitaire et les besoins des populations civiles.</p><h2>Chiffres et statistiques</h2><p>Selon les derniers rapports:</p><ul><li>Plus de 2 millions de personnes ont besoin d\'aide alimentaire</li><li>Secteur de la santé en crise permanente</li><li>Accès limité à l\'eau potable dans plusieurs régions</li></ul><h2>Actions internationaleschème</h2><p>Les organisations humanitaires internationales œuvrent pour améliorer la situation sur le terrain.</p>',
    'https://images.unsplash.com/photo-1488521787991-ed7fe863eac5?auto=format&fit=crop&w=1600&q=80',
    'Aide humanitaire en Iran',
    'Analyse de la crise humanitaire en Iran et impact sur la population civile.'
),
(
    'Diplomatie et négociations',
    'diplomatie-negociations',
    '<h1>Les efforts diplomatiques</h1><p>Les négociations internationales jouent un rôle central dans la recherche d\'une résolution pacifique.</p><h2>Acteurs clés</h2><p>Plusieurs pays et organisations internationales sont impliqués dans les pourparlers.</p><h3>Nations Unies</h3><p>L\'ONU maintient un rôle actif de médiation et de coordination des efforts humanitaires.</p><h3>Accords régionaux</h3><p>Des accords bilatéraux entre pays voisins visent à réduire les tensions.</p><h2>Perspectives futures</h2><p>Les experts évaluent les chances de succès sur la base d\'expériences passées.</p>',
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1600&q=80',
    'Table de négociations diplomatiques',
    'Exploration des efforts diplomatiques et des négociations pour résoudre le conflit.'
),
(
    'Impact économique régional',
    'impact-economique',
    '<h1>Les répercussions économiques</h1><p>Le conflit a d\'importantes implications économiques non seulement pour l\'Iran mais aussi pour la région entière.</p><h2>Secteurs affectés</h2><ul><li>Secteur pétrolier et énergétique</li><li>Commerce international</li><li>Investissements étrangers</li><li>Emploi et chômage</li></ul><h2>Sanctions économiques</h2><p>Les sanctions imposées ont considérablement limité les capacités économiques du pays.</p><blockquote>La stabilité économique est cruciale pour la paix durable.</blockquote><h2>Prévisions économistes</h2><p>Les analystes économiques proposent différents scénarios pour la reprise.</p>',
    'https://images.unsplash.com/photo-1454487967921-7aed57d495c1?auto=format&fit=crop&w=1600&q=80',
    'Graphique économique en baisse',
    'Analyse de l\'impact économique du conflit sur l\'Iran et la région.'
),
(
    'Population réfugiée et déplacés',
    'refugies-deplaces',
    '<h1>Les flux de réfugiés</h1><p>Des millions de personnes ont dû quitter leurs maisons en raison du conflit.</p><h2>Chiffres des déplacements</h2><p>Les statistiques montrent l\'ampleur des mouvements de population:</p><ul><li>Environ 3 millions de personnes déplacées internes</li><li>Milliers de réfugiés dans les pays voisins</li><li>Demandes d\'asile croissantes en Europe</li></ul><h2>Conditions dans les camps</h2><p>Les camps de réfugiés font face à d\'énormes défis logistiques et humanitaires.</p><h2>Droits et protection</h2><p>Des organismes internationaux surveillance le respect des droits humains.</p>',
    'https://images.unsplash.com/photo-1559027615-cd2628902d4a?auto=format&fit=crop&w=1600&q=80',
    'Familles de réfugiés',
    'Situation des réfugiés et des personnes déplacées par le conflit.'
);