# TODO: Tâches Frontoffice et Backoffice

## 🎯 Objectif Global
Créer un site d'informations complet sur la guerre en Iran avec une interface publique (FrontOffice) et une administration de contenu (BackOffice) entièrement fonctionnelle.

---

## 📋 FRONTOFFICE (Interface Publique)

### 1. Page d'Accueil (`frontoffice/index.php`)
- [ ] Créer un layout responsif avec en-tête fixe
- [ ] Afficher un héros/banner avec image et call-to-action
- [ ] Implémenter une section "Actualités en vedette" (3-4 articles épinglés)
- [ ] Ajouter une section de catégories/thèmes
- [ ] Créer un formulaire de newsletter (optionnel mais listable en BDD)
- [ ] Optimiser le SEO (meta tags, structured data)
- [ ] Implémenter le dark mode toggle avec localStorage
- [ ] Footer avec liens, contact, mentions légales

### 2. Liste des Articles (`frontoffice/articles-list.php`)
- [ ] Créer une grille/liste d'articles avec pagination
- [ ] Implémenter les filtres par catégorie (si applicable)
- [ ] Ajouter une barre de recherche avec autocomplete
- [ ] Afficher pour chaque article: titre, extrait, image, date, catégorie
- [ ] Lien "Lire la suite" pointant vers le détail (via slug)
- [ ] Badges pour articles récents/importants
- [ ] Tri par date, popularité, pertinence
- [ ] Affichage responsif (mobile-first)

### 3. Page Détail Article (`frontoffice/detail-article.php`) ✅
- [x] Récupérer les données via slug depuis la BDD
- [x] Afficher le contenu HTML enrichi (titres, paragraphes, listes, citations)
- [x] Afficher l'image héroïque avec alt text
- [x] Présenter les métadonnées: titre, date, auteur (si applicable)
- [ ] Ajouter une section "Articles recommandés" (articles similaires)
- [ ] Implémenter le système de partage sur réseaux sociaux
- [ ] Ajouter des commentaires/réactions (optionnel)
- [ ] Breadcrumb navigation
- [ ] Canonical tag pour SEO
- [x] History.replaceState pour conserver le slug en URL

### 4. Navigation et UX
- [ ] Menu principal responsive (hamburger sur mobile)
- [ ] Breadcrumb navigation sur les détails
- [ ] Pagination/navigation entre articles suivant/précédent
- [ ] Bouton "Retour" vers la liste
- [ ] Lien vers le BackOffice depuis la page publique

### 5. SEO et Performance
- [ ] Optimiser les images (lazy loading, WebP)
- [ ] Minifier CSS/JS
- [ ] Implémenter la compression gzip
- [ ] Tester avec Lighthouse (target: >85)
- [ ] Générer sitemap.xml dynamique
- [ ] Robots.txt approprié
- [ ] Canonical tags sur les pages principales

### 6. Accessibilité
- [ ] ARIA labels sur les boutons/liens
- [ ] Contraste des couleurs WCAG AA minimum
- [ ] Texte alternatif sur toutes les images
- [ ] Navigation au clavier fonctionnelle
- [ ] Tests avec WAVE/Axe

---

## 🔧 BACKOFFICE (Administration)

### 1. Authentification (`backoffice/login.php`) ✅ (partiellement)
- [x] Formulaire de connexion
- [ ] Hash sécurisé des mots de passe (bcrypt, pas plaintext!)
- [ ] Sessions PHP sécurisées avec timeouts
- [ ] Protection CSRF sur les formulaires
- [ ] Redirection automatique si déjà connecté
- [x] Redirection vers logout après connexion manquée

### 2. Logout (`backoffice/logout.php`) ✅
- [x] Détruire la session utilisateur
- [x] Rediriger vers la page publique

### 3. Dashboard/Page d'Accueil BO (`backoffice/dashboard.php`)
- [ ] Résumé des statistiques: nombre d'articles, visiteurs, articles récents
- [ ] Graphique de publication par mois
- [ ] Activation rapide d'articles importants
- [ ] Liens d'accès rapide vers le CRUD
- [ ] Dernier articles modifiés (widget recents)

### 4. CRUD Articles - Section Listing (`backoffice/articles.php`)
- [ ] Tableau listant tous les articles avec: ID, Titre, Slug, Date, Actions
- [ ] Pagination du tableau (50 articles par page)
- [ ] Tri par colonne (titre, date, ID)
- [ ] Moteur de recherche/filtrage par titre ou slug
- [ ] Statut de publication (brouillon/publié) - optionnel
- [ ] Boutons d'actions: Éditer, Supprime, Aperçu
- [ ] Confirmation avant suppression
- [ ] Bulk actions (suppression multiple)
- [ ] Indicateur de pages orphelines (anciennes)

### 5. CRUD Articles - Créer/Éditer (`backoffice/article-form.php`)
- [ ] Champ **Titre** (input text, 255 chars, requis)
- [ ] Champ **Slug** (auto-généré depuis titre, éditable manuellement)
  - Fonction de conversion: "Mon Titre" → "mon-titre"
  - Vérification d'unicité en temps réel (AJAX)
  - Validation: alphanumériques + tirets uniquement
- [ ] **Éditeur riche TinyMCE** pour le contenu principal
  - Intégration CDN TinyMCE
  - Boutons: H1, H2, H3, Gras, Italique, Listes, Blockquote, Liens, Images, Code
  - Validation HTML (pas de scripts)
  - Auto-save optionnel toutes les 30s
- [ ] Champ **Image URL** (URL externe ou upload)
- [ ] Champ **Image Alt** (pour l'accessibilité)
- [ ] Champ **Meta Description** (160 caractères, requis pour SEO)
- [ ] Champ **Date** (éditable, défaut = aujourd'hui)
- [ ] Preview en temps réel (side-by-side avec l'éditeur)
- [ ] Boutons: Sauvegarder, Sauvegarder et Fermer, Aperçu
- [ ] Validation du formulaire (client + serveur)
- [ ] Messages de succès/erreur clairs

### 6. Validation et Sécurité - Côté Serveur
- [ ] Échapper les entrées HTML via htmlspecialchars()
- [ ] Préparation des requêtes SQL (PDO prepared statements)
- [ ] Limitation de la longueur des champs (matching BDD)
- [ ] Validations métier:
  - Slug unique (vérifier en BDD)
  - Titre non vide
  - Slug format valide (a-z, 0-9, tirets)
  - Meta description ≤ 160 caractères
- [ ] Traçabilité: logger les modifications (optionnel: créer table audit)

### 7. Permissions et Contrôle d'Accès
- [ ] Vérifier la session utilisateur sur chaque page BO
- [ ] Redirection vers login si non authentifié
- [ ] Rôles (admin peut tout faire, editor peut gérer articles)
- [ ] Niveaux de permissions par action (créer, éditer, supprimer)

### 8. Gestion des Médias (optionnel mais recommandé)
- [ ] Uploader des images localement (au lieu d'URLs externes)
- [ ] Créer un dossier `/uploads` avec permissions appropriées
- [ ] Redimensionner les images automatiquement
- [ ] Générer des thumbnails pour la liste

### 9. Gestion des Slugs (.htaccess) - À NE PAS TOUCHER
- ⚠️ **Le BackOffice ne modifie PAS .htaccess**
- Le rewriting est déjà généralisé via:
  ```apache
  RewriteRule ^articles/([0-9]+)/?$ frontoffice/detail-article.php?id=$1 [L,QSA]
  ```
- Chaque nouvel article reçoit un ID auto-incrémenté en BDD
- Le slug de l'article est stocké en BDD et utilisé dans detail-article.php
- Les règles de rewriting de slugs (origines-conflit/ → articles/1) sont gérées statiquement en .htaccess par l'admin
- Le système FO récupère l'article par ID numérique, indépendamment du slug

---

## 🗄️ BASE DE DONNÉES

### Structure Existante ✅
```sql
utilisateurs(id, username, password, role, created_at)
articles(id, titre, slug, contenu, image_url, image_alt, meta_description, date_creation, date_modification)
```

### Données de Test ✅
- [x] 2 utilisateurs (admin + journalist)
- [x] 5 articles d'exemple avec contenu riche et métadonnées

### Améliorations Futures (non prioritaires)
- [ ] Table `catégories` si besoin de multiples thèmes
- [ ] Table `commentaires` si fonctionnalité commentaire
- [ ] Table `users_sessions` pour suivi des logins
- [ ] Table `audit_logs` pour traçabilité

---

## 🔄 URL Rewriting / Routing (.htaccess)

### État Actuel ✅
```apache
origines-conflit/ → articles/1
articles/([0-9]+)/ → frontoffice/detail-article.php?id=$1
```

### À Faire
- [ ] Ajouter les autres slugs au fur et à mesure (ou générer dynamiquement)
- [ ] Tester tous les cas: avec/sans slash, avec paramètres GET
- [ ] Documenter les règles pour futurs développeurs

---

## 🧪 TESTS

### Frontoffice
- [ ] Accueil charge correctement
- [ ] Liste des articles paginée
- [ ] Clic sur un article redirige vers son détail via slug
- [ ] Détail affiche contenu HTML correctement
- [ ] Images s'affichent
- [ ] Navigation responsive sur mobile
- [ ] SEO: tester meta tags, title, description
- [ ] Performance Lighthouse > 85

### Backoffice
- [ ] Login échoue avec mauvais identifiants
- [ ] Login réussit avec admin/admin123
- [ ] Créer un nouvel article
  - Vérifier slug auto-généré
  - Verifier dans BDD
  - Accéder depuis le frontoffice via slug
- [ ] Éditer un article (modifier titre, contenu)
- [ ] Supprimer un article
- [ ] Vérifier les logs erreurs si quelque chose échoue

---

## 📦 Livraison (31 Mars 2026)

- [ ] Tout le code dans Git (GitHub/GitLab public)
- [ ] Fichier `.zip` du projet fonctionnelle
- [ ] `docker-compose.yml` et `Dockerfile` testés
- [ ] `init.sql` réinisialise la BDD fraîche
- [ ] Documentation technique (PDF):
  - Screenshots FO et BO
  - Modèle BDD
  - Accès BO (identifiants par défaut)
  - Architecture générale
  - Instructions pour lancer Docker
- [ ] Numéros d'étudiants dans le document
- [ ] README.md dans le dossier racine

---

## 🚀 Priorisation (MVP → Complet)

### Phase 1 (MVP - Impératif)
1. [x] Init BDD et données
2. [x] Détail article avec rewriting slug
3. [ ] Login/Logout BO
4. [ ] CRUD articles complet
5. [ ] Éditem TinyMCE

### Phase 2 (Consolidation)
6. [ ] Accueil FO + Liste articles
7. [ ] Dashboard BO
8. [ ] Validation et sécurité renforcée
9. [ ] Tests et documentation

### Phase 3 (Polish)
10. [ ] SEO avancé (sitemap, structured data)
11. [ ] Accessibilité
12. [ ] Performance (Lighthouse)
13. [ ] Dark mode, UX améliorée

---

## 📝 Notes
- Toujours utiliser PDO prepared statements pour les requêtes
- Valider côté serveur (pas uniquement client)
- Logs d'erreurs dans `error_log`
- Pas de secrets dans le code (.env optionnel)
