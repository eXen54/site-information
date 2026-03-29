# Projet : Site d'informations sur la guerre en Iran

## Étudiants
- **Nekena** : Backend, Base de données, Infrastructure Docker
- **Faniry** : Frontend, SEO, URL Rewriting

## Rôle et Tâches de Nekena (Développeur Backend & DevOps)

Nekena est responsable de la fondation technique, de la gestion des données et du BackOffice permettant l'administration du site. Voici le détail de ses missions :

### 1. Infrastructure et Déploiement (Docker)
- [ ] **Environnement Conteneurisé** : Créer et configurer le `Dockerfile` pour un serveur Apache avec PHP 8, en activant les extensions PDO (pour MySQL) et le module `rewrite` (nécessaire pour le SEO).
- [ ] **Orchestration** : Écrire le fichier `docker-compose.yml` pour instancier deux services :
  - **web** : Le conteneur PHP/Apache exposant le port 8080.
  - **db** : Le conteneur MySQL 8.0, avec les variables d'environnement appropriées (mot de passe root, nom de la BDD `mrrojo_projet`, utilisateur).

### 2. Conception de la Base de Données
- [ ] **Création du Modèle Conceptuel (MCD)** : Modéliser la structure des données nécessaires.
- [ ] **Développement du script SQL (`init.sql`)** :
  - Table `utilisateurs` (id, username, password) pour sécuriser l'accès au BackOffice.
  - Table `articles` (id, titre, slug pour l'URL, contenu, image_url, image_alt, meta_description, date_creation).
- [ ] **Initialisation automatique** : Assurer que le script `init.sql` s'exécute automatiquement au lancement du conteneur MySQL via le dossier `/docker-entrypoint-initdb.d/`.

### 3. Développement du BackOffice (BO)
Le BackOffice sera situé dans le dossier `/backoffice` (ou similaire) et devra permettre la gestion complète du contenu.
- [ ] **Sécurisation de l'accès** :
  - Créer une page de connexion (`login.php`).
  - Vérifier les identifiants par rapport à la base de données.
  - Utiliser les sessions PHP (`$_SESSION`) pour restreindre l'accès aux seules personnes authentifiées.
- [ ] **Gestion des articles (CRUD)** :
  - **Create** : Formulaire permettant d'ajouter un nouvel article (génération automatique du `slug` à partir du titre).
  - **Read** : Tableau de bord listant tous les articles avec des options de modification/suppression.
  - **Update** : Formulaire pré-rempli pour modifier un article existant.
  - **Delete** : Script de suppression dans la base de données.
- [ ] **Gestion des médias** : Mettre en place la logique d'upload de fichiers pour les images des articles (gestion des dossiers de destination, vérification des extensions).

### 4. Contribution à la Documentation Technique
- [ ] **Architecture** : Rédiger la partie expliquant comment lancer le projet avec Docker.
- [ ] **Schéma BDD** : Intégrer la modélisation de la base de données dans le PDF final.
- [ ] **Accès** : Fournir l'URL du BackOffice, les identifiants de test (user/pass par défaut), et son Numéro Étudiant.
- [ ] **Illustrations** : Fournir les captures d'écran de l'interface du BackOffice.