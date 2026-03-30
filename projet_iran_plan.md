#  PLAN FINAL — PROJET IRAN (SPRINTS + DEV1/DEV2 + GIT)

---

#  ARCHITECTURE GLOBALE DU PROJET

##  DEV1 = BACKEND ENGINE
- BDD MySQL
- Auth admin
- CRUD articles / catégories / tags
- PHP logique + PDO
- docker-compose

##  DEV2 = FRONT + SEO + UX
- FrontOffice (site public)
- SEO + rewriting
- performance
- Lighthouse
- design

##  COMMUN
- intégration FO ↔ BDD
- Docker final
- doc technique
- livraison zip + git

---

#  SPRINT 0 — INITIALISATION (COMMUN)

###  Git
- main
- develop
- branches feature/*

###  Docker (structure globale)
- php-apache
- mysql
- phpmyadmin

 Branche :
- `feature/docker-compose`

---

#  SPRINT 1 — BASE TECHNIQUE (CRITIQUE)

##  DEV1 — BDD + BACKEND CORE

###  Database
- users
- articles
- categories
- tags
- article_tag (liaison)

 Branche :
- `feature/db-init`

---

###  PHP CORE
- PDO (`db.php`)
- config.php
- functions.php

 Branche :
- `feature/php-core`

---

##  DEV2 — STRUCTURE FRONT

###  Pages FO (structure vide)
- index.php
- article.php
- categorie.php

 Branche :
- `feature/fo-structure`

---

#  SPRINT 2 — AUTH + CRUD ARTICLES (FONDATION)

##  DEV1 — BACKOFFICE

###  Auth admin
- login.php
- sessions
- logout

 `feature/bo-auth`

---

###  CRUD ARTICLES (CRITIQUE)
- create article
- read
- update
- delete
- upload image
- slug generation

 `feature/bo-crud-articles`

---

###  CRUD catégories + tags
 `feature/bo-taxonomy`

---

##  DEV2 — FRONT AFFICHAGE

###  Front articles
- liste articles
- page article dynamique (slug)

 `feature/fo-articles-display`

---

#  SPRINT 3 — URL REWRITING + SEO CORE

##  DEV2 (PRIORITÉ MAJEURE)

###  URL rewriting

/article/guerre-iran-2026
/categorie/politique

 `.htaccess`

 `feature/seo-rewrite`

---

###  SEO ON PAGE
- title dynamique
- meta description
- h1 unique
- alt images
- canonical

 `feature/seo-onpage`

---

##  DEV1 (support)

- slug propre en DB
- requêtes by slug

---

#  SPRINT 4 — BACKOFFICE AVANCÉ + FRONT PROPRE

##  DEV1

###  Dashboard BO
- stats articles
- derniers articles

 `feature/bo-dashboard`

---

###  Upload médias
- images
- alt_text
- compression base

 `feature/bo-media`

---

##  DEV2

###  UI FRONT
- CSS journal style
- responsive mobile
- layout clean

 `feature/fo-ui`

---

###  performance
- lazy loading
- WebP
- compression images

 `feature/seo-performance`

---

#  SPRINT 5 — SEO AVANCÉ + PERFORMANCE

##  DEV2 (CRITIQUE POUR NOTE)

###  SEO avancé
- sitemap.xml dynamique
- robots.txt
- schema.org JSON-LD

 `feature/seo-advanced`

---

###  Lighthouse optimization
- mobile
- desktop

 `feature/seo-lighthouse`

---

##  DEV1

- optimisation requêtes SQL
- index DB

---

#  SPRINT 6 — INTÉGRATION FINALE

##  COMMUN

###  FO ↔ BDD
- PDO intégré FO
- articles dynamiques

 `feature/integration-fo-db`

---

###  Docker final test
- docker-compose up
- site fonctionnel complet

---

###  DOC TECHNIQUE
- screenshots FO + BO
- modèle DB
- login BO
- ETU

 `docs/technical`

---

#  SPRINT 7 — LIVRAISON

- tag v1.0
- zip final
- push GitHub/GitLab public

---

##  ORDRE RÉEL DE DÉVELOPPEMENT

### 1. Docker + DB
### 2. Auth BO
### 3. CRUD articles
### 4. Front affichage
### 5. rewriting SEO
### 6. polish + perf
### 7. doc + livraison

---

#  ARCHITECTURE / STRUCTURE DU PROJET

```
project/
│
├── docker/
│   ├── php/
│   ├── apache/
│   └── mysql/
│
├── src/
│   ├── public/
│   │   ├── index.php
│   │   ├── article.php
│   │   ├── categorie.php
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   ├── js/
│   │   │   └── images/
│   │
│   ├── admin/
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── articles/
│   │   ├── categories/
│   │
│   ├── includes/
│   │   ├── db.php
│   │   ├── config.php
│   │   ├── functions.php
│   │
│   ├── models/
│   ├── controllers/
│   └── views/
│
├── database/
│   └── init.sql
│
├── docker-compose.yml
├── Dockerfile
├── .htaccess
├── README.md
└── TECHNICAL_DOC.md
```

