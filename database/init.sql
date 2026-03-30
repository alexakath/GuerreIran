-- import de la base dans docker :
-- docker exec -i iran_mysql mysql -u root -pYOUR_PASSWORD dbname < database/init.sql

-- slug = URL propre
-- Titre: Guerre en Iran aujourd’hui
-- Slug: guerre-iran-aujourdhui
-- URL: /article/guerre-iran-aujourdhui

-- 1 article → 1 catégorie
-- 1 article → plusieurs tags
-- tags → réutilisables
-- users → backoffice

CREATE DATABASE IF NOT EXISTS guerre_iran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE guerre_iran;

CREATE TABLE IF NOT EXISTS users ENGINE=InnoDB (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'author', 'editor') NOT NULL DEFAULT 'author',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories ENGINE=InnoDB (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- tag = mot-clé libre
CREATE TABLE IF NOT EXISTS tags ENGINE=InnoDB (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS articles ENGINE=InnoDB (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- many-to-many entre articles et tags
CREATE TABLE IF NOT EXISTS article_tags ENGINE=InnoDB (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- index sur articles pour les recherches rapides
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_category ON articles(category_id);
CREATE INDEX idx_articles_author ON articles(author_id);
