# Plan de tâches - Nekena

*Rôle : Développeur Backend & DevOps*

## Tâche 1 : Infrastructure & Versionnement
- [ ] Créer le dépôt public sur GitHub/GitLab et inviter Faniry.
- [ ] Mettre en place les fichiers Docker (Dockerfile pour PHP/Apache, `docker-compose.yml` avec les services `php-apache` et `mysql` ou `mariadb`).
- [ ] S'assurer que le module `rewrite` d'Apache est activé dans le Dockerfile.

## Tâche 2 : Base de Données
- [ ] Créer le Modèle Conceptuel de Données (MCD) et le script SQL.
  - *Tables suggérées : `utilisateurs` (pour le login BO), `articles` (titre, contenu, url_slug, image, alt_image, meta_description).*
- [ ] Initialiser la BDD avec des données par défaut via Docker (fichier `init.sql`).

## Tâche 3 : Développement du BackOffice
- [ ] Créer la page de connexion (`/backoffice/login.php`) avec un User/Password par défaut.
- [ ] Sécuriser l'accès au BO via les sessions PHP (`$_SESSION`).
- [ ] Développer les pages CRUD (Créer, Lire, Modifier, Supprimer) :
  - Formulaire d'ajout/modification d'articles.
  - Champ obligatoire pour le titre, les métadonnées, l'image et l'attribut `alt`.
  - Script d'upload des images.

## Tâche 4 : Documentation Technique (Partie 1)
- [ ] Rédiger la section "Infrastructure" du document technique.
- [ ] Fournir le schéma de modélisation de la BDD.
- [ ] Intégrer les identifiants BO par défaut et nos N° Étudiants.
- [ ] Prendre les captures d'écran du BackOffice.
