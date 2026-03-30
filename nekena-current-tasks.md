# Plan d'Action Actuel pour Nekena (Backend & BackOffice)

Suite à l'avancement du projet (FrontOffice partiellement validé, structure BDD prête, `docker` initialisé, Login/Logout basique), voici les tâches urgentes que tu dois accomplir **dès maintenant** pour le BackOffice.

## 🎯 TOP PRIORITÉ : Le CRUD Articles (Create, Read, Update, Delete)

C'est le cœur de ta mission. Tu dois terminer l'interface d'administration permettant de gérer les articles du site.

### 1. Le Dashboard du BackOffice (`backoffice/dashboard.php`)
- [ ] Créer une page d'accueil simple pour le BO avec un résumé (ex: "Nombre total d'articles : X").

### 2. Liste des Articles (`backoffice/articles.php`)
- [ ] Créer un tableau listant tous les articles de la BDD (ID, Titre, Slug, Date de création).
- [ ] Ajouter des boutons d'action sur chaque ligne : **Éditer**, **Supprimer**, et un lien pour **Voir** l'article sur le FrontOffice (via son URL rewrite `../articles/ID`).

### 3. Formulaire de Création / Édition (`backoffice/article-form.php`) avec TinyMCE CDN
C'est ici qu'intervient la **nouvelle exigence majeure** :
- [ ] Intégrer **TinyMCE via CDN** pour l'édition du champ "Contenu".
  - Ne télécharge pas les fichiers sources, utilise le script CDN direct dans ton `<head>`.
  - Configure la barre d'outils TinyMCE pour n'autoriser que l'essentiel : Titres (H2, H3), Gras, Italique, Listes, Citations et Liens.
- [ ] Gérer le champ **Titre**.
- [ ] Gérer le **Slug** : 
  - Il doit se générer automatiquement à partir du titre en JS (ex: "La Guerre" -> "la-guerre"), tout en restant modifiable à la main.
  - Vérifier côté PHP que le slug contient uniquement des caractères valides (a-z, 0-9, tirets).
- [ ] Gérer les métadonnées (Image URL, Image Alt, Meta description).
- [ ] **Sécurité Serveur** : Utiliser impérativement des requêtes préparées PDO (jamais de concaténation directe) et valider/nettoyer les inputs avant l'insertion en BDD.

### 4. Gestion de l'Upload d'Images (Requis)
- [ ] Dans le formulaire de création/édition, utiliser un champ de type `file` (ne pas oublier `enctype="multipart/form-data"` sur le `<form>`).
- [ ] Gérer l'upload en PHP (`$_FILES`) de manière sécurisée :
  - Vérifier les erreurs d'upload.
  - Valider l'extension de l'image (ex: jpg, jpeg, png, webp).
  - Limiter la taille du fichier (ex: 2 Mo maximum).
  - Générer un nom de fichier unique (via `uniqid()`) pour éviter d'écraser des fichiers existants.
- [ ] Déplacer l'image validée dans le répertoire `/uploads/` avec `move_uploaded_file()`.
- [ ] Enregistrer le chemin d'accès de la nouvelle image dans le champ `image_url` en BDD.

### 5. Logique de Suppression
- [ ] Créer un script (ex: `delete-article.php`) qui supprime un article selon son ID.
- [ ] Toujours demander une confirmation JS (`confirm()`) avant d'exécuter la suppression pour éviter les erreurs.
- [ ] Penser à également supprimer le fichier image associé dans le dossier `/uploads` lors de la suppression de l'article dans la BDD (avec la fonction `unlink()`).

## 🛠️ Traitements Optionnels et Polissage (Si le temps le permet)
- [ ] **Gestion des rôles** : Différencier Admin (peut créer des users) et Éditeur (gère seulement les articles).

---
## 🚀 Comment Démarrer le Projet (Instructions Docker)

Pour que toute l'équipe (ou le professeur) puisse lancer le projet facilement, voici les instructions de démarrage :

1. **Construire et lancer les conteneurs (en arrière-plan) :**
   ```bash
   docker-compose up -d --build
   ```
2. **Accéder à l'application web :**
   - FrontOffice : `http://localhost:8080/` (ou le port défini dans `docker-compose.yml`)
   - BackOffice : `http://localhost:8080/backoffice/`
3. **Accéder à la base de données :**
   La BDD se construira automatiquement grâce au fichier `init.sql`. Tu peux t'y connecter via l'hôte `localhost` (ou l'IP de docker) et le port SQL (ex: 3306) avec les credentials définis dans le `docker-compose.yml`.
4. **Arrêter l'application propre :**
   ```bash
   docker-compose down
   ```

---
**Rappel important :** Ne modifie pas le `.htaccess` pour les slugs. Le BackOffice se contente de stocker le slug généré en base de données. Le Routing est déjà fonctionnel.