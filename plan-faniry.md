# Plan de tâches - Faniry

*Rôle : Développeur Front-End & Expert SEO*

## Tâche 1 : Développement du FrontOffice
- [ ] Cloner le dépôt Git mis en place par Nekena.
- [ ] Créer et designer les maquettes des pages publiques (Thème : La guerre en Iran).
- [ ] Connecter le FrontOffice à la base de données via PHP (PDO ou MySQLi).
- [ ] Créer la page d'accueil listant les derniers articles.
- [ ] Créer la page dynamique pour afficher les détails d'un article.

## Tâche 2 : Optimisation SEO & Structure (Très Important)
- [ ] Assurer la structure sémantique des pages :
  - Unicité du `<h1>` par page.
  - Sous-titres corrects (`<h2>` à `<h6>`).
- [ ] Rendre les balises `<title>` et `<meta name="description">` dynamiques en fonction des articles.
- [ ] Vérifier que toutes les balises `<img>` utilisent bien l'attribut `alt` venant de la BDD.

## Tâche 3 : Configuration de l'URL Rewriting
- [ ] Analyser le fichier texte fourni (`sample-rewriting.txt`).
- [ ] Créer le fichier `.htaccess` à la racine du projet.
- [ ] Transformer les URLs du type `article.php?id=1` en URLs SEO-friendly comme `/article/1-guerre-en-iran.html`.
- [ ] Mettre à jour tous les liens dans le FrontOffice pour utiliser ces nouvelles URLs "propres".

## Tâche 4 : Tests & Documentation Technique (Partie 2)
- [ ] Lancer des audits **Lighthouse local** sur Google Chrome (Catégories : Performance et SEO) sur Mobile et Ordinateur.
- [ ] Corriger le code selon les recommandations Lighthouse (contraste, taille des textes, balises...).
- [ ] Prendre les captures d'écran du FrontOffice et des résultats Lighthouse.
- [ ] Finaliser l'assemblage du Document Technique en un seul PDF soigné avec Nekena.
- [ ] Générer le zip final (.zip) sans le dossier `vendor` ou `node_modules` et avec le projet testé sous Docker.
