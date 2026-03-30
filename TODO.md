# TODO par personne — Branches Git

**Binôme**: ETU003306 - Alexandra (FrontOffice, SEO, Docker PHP) & ETU003211 - Malala (BackOffice, BDD, Docker Compose)

## 7.1 Binômier 1 — FrontOffice + SEO + Docker PHP (ETU003306 - Alexandra)
Responsable des pages publiques, de l'optimisation SEO et du Dockerfile PHP/Apache.

1. Structure HTML FrontOffice
	- Fichiers: `index.php`, `article.php`, `categorie.php`, `chronologie.php`
	- Branche: `feature/fo-structure-html` — Priorité: Critique — Statut: A faire

2. CSS sobre (style journal)
	- Fichiers: `style.css`, responsive, critical CSS
	- Branche: `feature/fo-css-design` — Priorité: Haute — Statut: A faire

3. Page d'accueil FO
	- Fonctionnalités: liste d'articles, mise en une, pagination
	- Branche: `feature/fo-page-accueil` — Priorité: Critique — Statut: A faire

4. Page article FO
	- Fonctionnalités: affichage du contenu, breadcrumb, tags
	- Branche: `feature/fo-page-article` — Priorité: Critique — Statut: A faire

5. Page catégorie FO
	- Fonctionnalités: filtrage par catégorie, tri par date
	- Branche: `feature/fo-page-categorie` — Priorité: Haute — Statut: A faire

6. Page chronologie FO
	- Fonctionnalités: frise d'événements triés par date
	- Branche: `feature/fo-page-chronologie` — Priorité: Haute — Statut: A faire

7. Balises SEO on-page
	- Élément: `title`, `meta description`, `H1→H6`, `alt` images, `canonical`
	- Branche: `feature/seo-balises-onpage` — Priorité: Critique — Statut: A faire

8. .htaccess URL rewriting FO
	- Routes: `/article/slug`, `/categorie/slug`, redirections 301
	- Branche: `feature/seo-htaccess-fo` — Priorité: Critique — Statut: A faire

9. robots.txt + sitemap.xml
	- Sitemap dynamique PHP depuis BDD
	- Branche: `feature/seo-robots-sitemap` — Priorité: Haute — Statut: A faire

10. Schema.org JSON-LD
	 - Types: `Article`, `BreadcrumbList` sur chaque page
	 - Branche: `feature/seo-schema-jsonld` — Priorité: Haute — Statut: A faire

11. Optimisation performances
	 - Techniques: GZip via `.htaccess`, cache headers, lazy loading, WebP
	 - Branche: `feature/seo-performance` — Priorité: Haute — Statut: A faire

12. Tests Lighthouse
	 - Mobile + Desktop, captures pour le document technique
	 - Branche: `feature/seo-lighthouse-tests` — Priorité: Haute — Statut: A faire

13. Dockerfile PHP + Apache
	 - Baseline: image `php:8.2-apache` (ou `php:8.1`), vhost, `mod_rewrite` activé
	 - Branche: `feature/docker-php-apache` — Priorité: Critique — Statut: A faire

## 7.2 Binômier 2 — BackOffice + BDD + Docker Compose (ETU003211 - Malala)
Responsable de la base de données, de l'interface d'administration et du `docker-compose.yml`.

1. Import BDD MySQL
	- Fichiers: `database.sql`, contraintes FK, données démo
	- Branche: `feature/db-init-mysql` — Priorité: Critique — Statut: A faire

2. Couche PHP partagée
	- Fichiers: `includes/db.php` (PDO), `config.php`, `functions.php`
	- Branche: `feature/db-pdo-includes` — Priorité: Critique — Statut: A faire

3. Page login BackOffice
	- Fichiers: `login.php`, sessions PHP, token CSRF, logout
	- Branche: `feature/bo-auth-login` — Priorité: Critique — Statut: A faire

4. Dashboard BackOffice
	- Contenu: stats articles, nombre de vues, derniers commentaires
	- Branche: `feature/bo-dashboard` — Priorité: Haute — Statut: A faire

5. CRUD Articles (BO)
	- Fonctionnalités: créer, lire, modifier, supprimer, upload d'images
	- Branche: `feature/bo-crud-articles` — Priorité: Critique — Statut: A faire

6. CRUD Catégories (BO)
	- Fonctionnalités: gestion des catégories, slugs auto-générés
	- Branche: `feature/bo-crud-categories` — Priorité: Haute — Statut: A faire

7. CRUD Tags (BO)
	- Fonctionnalités: gestion des tags, table de liaison `articles_tags`
	- Branche: `feature/bo-crud-tags` — Priorité: Moyenne — Statut: A faire

8. Gestion chronologie (BO)
	- Fonctionnalités: CRUD événements, liaison optionnelle avec articles
	- Branche: `feature/bo-crud-chronologie` — Priorité: Moyenne — Statut: A faire

9. Modération commentaires
	- Fonctionnalités: approuver, rejeter, supprimer depuis le BO
	- Branche: `feature/bo-moderation-commentaires` — Priorité: Haute — Statut: A faire

10. .htaccess URL rewriting BO
	 - Règle: protéger `/admin/*`, rediriger si non connecté
	 - Branche: `feature/seo-htaccess-bo` — Priorité: Haute — Statut: A faire

11. Upload et gestion médias
	 - Fonctionnalités: upload image, redimensionnement, `alt_text`
	 - Branche: `feature/bo-upload-medias` — Priorité: Haute — Statut: A faire

12. docker-compose.yml
	 - Services: `php-apache`, `mysql:8.0`, `phpmyadmin`
	 - Branche: `feature/docker-compose` — Priorité: Critique — Statut: A faire

## 7.3 Tâches communes — À faire ensemble

1. Initialisation dépôt Git
	- Fichiers: `README.md`, `.gitignore`, branches `main`/`develop`
	- Branche: `main` → `develop` — Priorité: Critique — Statut: A faire

2. Intégration FO ↔ BDD
	- Actions: connexion PDO dans pages FO, requêtes par slug
	- Branche: `feature/integration-fo-db` — Priorité: Critique — Statut: A faire

3. Document technique
	- Contenu: captures d'écran FO+BO, modélisation BDD, ETU, user/pass
	- Branche: `docs/document-technique` — Priorité: Critique — Statut: A faire

4. Zip de livraison final
	- Processus: `docker-compose up` → vérification site fonctionnel, tag `v1.0`
	- Branche: `main` (tag `v1.0`) — Priorité: Critique — Statut: A faire

---

Notes:
- Chaque tâche doit avoir une branche dédiée (nommage `feature/...` ou `docs/...`).
- Faire des PR vers `develop`, puis `main` pour la livraison finale.
- Inclure les numéros ETU et identifiants BO par défaut dans `TECHNICAL_DOC.md`.

