-- Seed test data: users, categories, tags, articles, article_tags
USE guerre_iran;

-- Users
INSERT INTO users (username,email,password,role) VALUES
('admin','admin@example.com','password','admin')
ON DUPLICATE KEY UPDATE username=VALUES(username);

-- Categories
INSERT INTO categories (name,slug) VALUES
('Politique','politique'),
('Monde','monde')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Tags
INSERT INTO tags (name,slug) VALUES
('Iran','iran'),
('Conflit','conflit'),
('Diplomatie','diplomatie')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Articles
INSERT INTO articles (title,slug,content,image_url,category_id,author_id) VALUES
('Guerre en Iran : état des lieux','guerre-iran-etat-des-lieux','<p>Un aperçu complet des événements récents en Iran.</p>','',1,1),
('Tensions diplomatiques régionales','tensions-diplomatiques','<p>Analyse des réactions internationales.</p>','',2,1),
('Impact humanitaire et réponses','impact-humanitaire','<p>Que font les ONG et la communauté internationale ?</p>','',1,1)
ON DUPLICATE KEY UPDATE title=VALUES(title), content=VALUES(content);

-- Map tags to articles (assume article ids 1..3 and tag ids 1..3; if not, use lookups)
-- We'll perform lookup inserts to be robust
-- Article 1: tags Iran, Conflit
INSERT INTO article_tags (article_id, tag_id)
SELECT a.id, t.id FROM (SELECT id FROM articles WHERE slug='guerre-iran-etat-des-lieux') a
JOIN (SELECT id FROM tags WHERE slug IN ('iran','conflit')) t ON 1=1
ON DUPLICATE KEY UPDATE article_id=article_id;

-- Article 2: tag Diplomatie
INSERT INTO article_tags (article_id, tag_id)
SELECT a.id, t.id FROM (SELECT id FROM articles WHERE slug='tensions-diplomatiques') a
JOIN (SELECT id FROM tags WHERE slug='diplomatie') t ON 1=1
ON DUPLICATE KEY UPDATE article_id=article_id;

-- Article 3: tags Iran, Diplomatie
INSERT INTO article_tags (article_id, tag_id)
SELECT a.id, t.id FROM (SELECT id FROM articles WHERE slug='impact-humanitaire') a
JOIN (SELECT id FROM tags WHERE slug IN ('iran','diplomatie')) t ON 1=1
ON DUPLICATE KEY UPDATE article_id=article_id;

-- Ensure slugs indexed (already in init.sql)
