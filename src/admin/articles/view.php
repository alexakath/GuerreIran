<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();
send_security_headers();

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$article = null;
if ($slug) {
    // Lookup by slug using helper
    $article = find_article_by_slug($pdo, $slug);
    if ($article) {
        $id = (int)$article['id'];
    } else {
        redirect('list.php');
    }
}

if ($id <= 0) redirect('list.php');

if (!$article) {
    $stmt = $pdo->prepare("SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    if (!$article) redirect('list.php');
}

$tagStmt = $pdo->prepare("SELECT t.* FROM tags t JOIN article_tags at ON at.tag_id = t.id WHERE at.article_id = ? ORDER BY t.name ASC");
$tagStmt->execute([$id]);
$tags = $tagStmt->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail article — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
    <link rel="stylesheet" href="/assets/css/admin.article.css">
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <?php $pageTitle = 'Détail article'; require_once __DIR__ . '/../_header.php'; ?>

            <main class="container">
                <div class="article-detail">
                        <div class="detail-main">
                            <div class="detail-header-row">
                                <h1><?php echo e($article['title']); ?></h1>
                                <div class="published">Publié: <?php echo e($article['created_at']); ?></div>
                            </div>

                            <div class="slug"><?php echo e($article['slug']); ?></div>

                            <div style="margin-top:8px">
                                <span class="category-badge"><?php echo e($article['category_name'] ?? '—'); ?></span>
                            </div>

                            <div class="chips" style="margin-top:10px">
                                <?php foreach ($tags as $t): ?>
                                    <span class="chip"><?php echo e($t['name']); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="article-content">
                                <?php echo $article['content']; ?>
                            </div>
                            

                            <div class="actions">
                                <a class="btn" href="/admin/articles/<?php echo e($article['slug']); ?>/edit">Éditer</a>
                                <form method="post" action="/admin/articles/<?php echo e($article['slug']); ?>/delete" onsubmit="return confirm('Supprimer cet article ?');" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="slug" value="<?php echo e($article['slug']); ?>">
                                    <button type="submit" class="btn btn-ghost btn-danger">Supprimer</button>
                                </form>
                                <a class="quick-link" href="list.php">← Retour à la liste</a>
                            </div>

                            
                        </div>

                    <aside class="detail-aside">
                        <?php if (!empty($article['image_url'])): ?>
                            <img src="/<?php echo e($article['image_url']); ?>" alt="">
                        <?php else: ?>
                            <div class="thumb--empty">Pas d'image</div>
                        <?php endif; ?>
                    </aside>
                </div>
            </main>

            <?php require_once __DIR__ . '/../_footer.php'; ?>
        </div>
    </div>

    <script>
        const hamb = document.querySelector('.hamburger');
        const navActions = document.querySelector('.nav-actions');
        if (hamb) {
            hamb.addEventListener('click', () => {
                const expanded = hamb.getAttribute('aria-expanded') === 'true';
                hamb.setAttribute('aria-expanded', String(!expanded));
                if (navActions) navActions.classList.toggle('open');
            });
        }
    </script>
</body>
</html>
