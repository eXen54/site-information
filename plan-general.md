# Plan Général : Site d'informations sur la guerre en Iran

## 1. Objectifs du projet
- Développer un site web informatif (FrontOffice).
- Développer un système de gestion de contenu (BackOffice).
- Assurer un référencement technique optimal (SEO, URL Rewriting).
- Livrer le projet conteneurisé (Docker), versionné (Git) avec sa documentation technique.

## 2. Stack Technique Décidée
- **Langages :** PHP 8, HTML5, CSS3, JavaScript (optionnel).
- **Serveur Web :** Apache (nécessaire pour l'URL Rewriting via `.htaccess`).
- **Base de données :** MySQL / MariaDB.
- **Infrastructure :** Docker & Docker Compose (Conteneurs `web` et `db`).
- **Versionnement :** Git (GitHub ou GitLab public).

## 3. Architecture Globale
- `/front` : Vues publiques (Accueil, Liste des articles, Détail d'un article).
- `/backoffice` : Espace sécurisé par mot de passe pour gérer les articles.
- `/rewriting` ou à la racine : Fichier `.htaccess` pour gérer les jolies URLs.
- `docker-compose.yml` & `Dockerfile` : Pour l'environnement de déploiement.

## 4. Jalons & Timeline (Livraison : Mardi 31 Mars à 14h00)
- **Étape 1 :** Mise en place du repo Git, modélisation BDD et config Docker.
- **Étape 2 :** Développement du BO (CRUD) et insertion du contenu.
- **Étape 3 :** Développement du FO et appel des données.
- **Étape 4 :** Implémentation du SEO et de l'URL Rewriting.
- **Étape 5 :** Tests Lighthouse, rédaction de la documentation technique et création du Zip.

## 5. Livrables Attendus
1. Un fichier `.zip` contenant le site fonctionnel sur Docker.
2. Un lien vers le dépôt en ligne (GitHub / GitLab).
3. Un document technique (PDF de préférence) incluant :
   - Captures d'écran FO et BO.
   - Modélisation de la base de données.
   - Accès BackOffice (URL, user/pass par défaut).
   - Numéros étudiants.
