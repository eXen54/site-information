DROP DATABASE IF EXISTS info_iran;
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
    published_by INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (published_by) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX (slug),
    INDEX (date_creation),
    INDEX (published_by)
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
    '<h1>Les origines du conflit</h1><p>Le conflit en Iran a des racines historiques complexes remontant a plusieurs siecles. Cet article examine les evenements cles qui ont mene a la situation actuelle.</p><h2>Contexte historique</h2><p>Au cours du XXeme siecle, l\'Iran a connu d\'importantes transformations politiques et sociales qui ont façonne le paysage contemporain.</p><blockquote>La comprehension du passe est essentielle pour anticiper l\'avenir.</blockquote><h2>Facteurs geopolitiques</h2><p>Les puissances regionales et internationales ont joue un rôle crucial dans l\'evolution du conflit.</p>',
    'uploads/origines-conflit.jpg',
    'Manifestation iranienne pour les droits civiques',
    'Decouvrez les origines historiques du conflit en Iran et ses facteurs geopolitiques.'
),
(
    'Situation humanitaire et civils',
    'situation-humanitaire',
    '<h1>La crise humanitaire en Iran</h1><p>Des millions de personnes sont affectees par les tensions actuelles. Cette analyse se concentre sur l\'impact humanitaire et les besoins des populations civiles.</p><h2>Chiffres et statistiques</h2><p>Selon les derniers rapports:</p><ul><li>Plus de 2 millions de personnes ont besoin d\'aide alimentaire</li><li>Secteur de la sante en crise permanente</li><li>Acces limite a l\'eau potable dans plusieurs regions</li></ul><h2>Actions internationalescheme</h2><p>Les organisations humanitaires internationales œuvrent pour ameliorer la situation sur le terrain.</p>',
    'uploads/situation-humanitaire-et-civils.jpg',
    'Aide humanitaire en Iran',
    'Analyse de la crise humanitaire en Iran et impact sur la population civile.'
),
(
    'Diplomatie et negociations',
    'diplomatie-negociations',
    '<h1>Les efforts diplomatiques</h1><p>Les negociations internationales jouent un rôle central dans la recherche d\'une resolution pacifique.</p><h2>Acteurs cles</h2><p>Plusieurs pays et organisations internationales sont impliques dans les pourparlers.</p><h3>Nations Unies</h3><p>L\'ONU maintient un rôle actif de mediation et de coordination des efforts humanitaires.</p><h3>Accords regionaux</h3><p>Des accords bilateraux entre pays voisins visent a reduire les tensions.</p><h2>Perspectives futures</h2><p>Les experts evaluent les chances de succes sur la base d\'experiences passees.</p>',
    'uploads/diplomatie-et-negociations.jpg',
    'Table de negociations diplomatiques',
    'Exploration des efforts diplomatiques et des negociations pour resoudre le conflit.'
),
(
    'Impact economique regional',
    'impact-economique',
    '<h1>Les repercussions economiques</h1><p>Le conflit a d\'importantes implications economiques non seulement pour l\'Iran mais aussi pour la region entiere.</p><h2>Secteurs affectes</h2><ul><li>Secteur petrolier et energetique</li><li>Commerce international</li><li>Investissements etrangers</li><li>Emploi et chômage</li></ul><h2>Sanctions economiques</h2><p>Les sanctions imposees ont considerablement limite les capacites economiques du pays.</p><blockquote>La stabilite economique est cruciale pour la paix durable.</blockquote><h2>Previsions economistes</h2><p>Les analystes economiques proposent differents scenarios pour la reprise.</p>',
    'uploads/impact-economique-regional.png',
    'Graphique economique en baisse',
    'Analyse de l impact economique du conflit sur l Iran et la region.'
),
(
    'Population refugiee et deplaces',
    'refugies-deplaces',
    '<h1>Les flux de refugies</h1><p>Des millions de personnes ont dû quitter leurs maisons en raison du conflit.</p><h2>Chiffres des deplacements</h2><p>Les statistiques montrent l\'ampleur des mouvements de population:</p><ul><li>Environ 3 millions de personnes deplacees internes</li><li>Milliers de refugies dans les pays voisins</li><li>Demandes d\'asile croissantes en Europe</li></ul><h2>Conditions dans les camps</h2><p>Les camps de refugies font face a d\'enormes defis logistiques et humanitaires.</p><h2>Droits et protection</h2><p>Des organismes internationaux surveillance le respect des droits humains.</p>',
    'uploads/population-refugiee-et-deplaces.jpg',
    'Familles de refugies',
    'Situation des refugies et des personnes deplacees par le conflit.'
);