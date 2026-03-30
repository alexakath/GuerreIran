<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();
send_security_headers();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];

// Pagination
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Search + filters
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$categoryFilter = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$tagFilterIds = [];
if (isset($_GET['tags']) && is_array($_GET['tags'])) {
    $tagFilterIds = array_map('intval', $_GET['tags']);
}

// fetch categories & all tags for filter controls
try {
    $catsStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $catsStmt->fetchAll();
} catch (Exception $e) {
    $categories = [];
}
try {
    $tagsStmt = $pdo->query("SELECT id, name FROM tags ORDER BY name ASC");
    $allTags = $tagsStmt->fetchAll();
} catch (Exception $e) {
    $allTags = [];
}

// Build where clause and params (support q, category, tags)
$whereParts = [];
$params = [];
if ($q !== '') {
    $whereParts[] = "(a.title LIKE :q OR a.slug LIKE :q OR a.content LIKE :q OR c.name LIKE :q)";
    $params[':q'] = "%{$q}%";
}
if ($categoryFilter > 0) {
    $whereParts[] = "a.category_id = :category_id";
    $params[':category_id'] = $categoryFilter;
}
if (!empty($tagFilterIds)) {
    // create named placeholders for each tag id
    $tagPlaceholders = [];
    foreach ($tagFilterIds as $i => $tid) {
        $ph = ':tag' . $i;
        $tagPlaceholders[] = $ph;
        $params[$ph] = $tid;
    }
    $whereParts[] = "EXISTS (SELECT 1 FROM article_tags at WHERE at.article_id = a.id AND at.tag_id IN (" . implode(',', $tagPlaceholders) . "))";
}
$where = '';
if (!empty($whereParts)) {
    $where = 'WHERE ' . implode(' AND ', $whereParts);
}

// Count total
$total = 0;
try {
    $countSql = "SELECT COUNT(*) FROM articles a LEFT JOIN categories c ON a.category_id = c.id $where";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();
} catch (Exception $e) {
    $total = 0;
}

$sql = "SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id $where ORDER BY a.id DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();

// fetch tags for displayed articles (map)
$tagsMap = [];
$articleIds = array_column($articles, 'id');
if (!empty($articleIds)) {
    $placeholders = implode(',', array_fill(0, count($articleIds), '?'));
    $tagStmt = $pdo->prepare("SELECT at.article_id, t.id, t.name FROM article_tags at JOIN tags t ON t.id = at.tag_id WHERE at.article_id IN ($placeholders) ORDER BY t.name ASC");
    $tagStmt->execute($articleIds);
    $tagRows = $tagStmt->fetchAll();
    foreach ($tagRows as $r) {
        $tagsMap[$r['article_id']][] = $r;
    }
}

$totalPages = $perPage ? (int) ceil($total / $perPage) : 1;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Articles — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <?php $pageTitle = 'Articles'; require_once __DIR__ . '/../_header.php'; ?>
            

            <main class="container">
                
                <header class="page-header">
                    <div class="title">Articles</div>
                    <div class="header-controls">
                        <form method="get" class="search-filters">
                            <input type="search" name="q" class="search-input" placeholder="Rechercher titre, contenu, slug ou catégorie" value="<?php echo e($q); ?>">

                            <select name="category_id" class="filter-select">
                                <option value="0">Toutes catégories</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($categoryFilter == $c['id']) ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select name="tags[]" multiple size="3" class="tag-select">
                                <?php foreach ($allTags as $t): ?>
                                    <option value="<?php echo $t['id']; ?>" <?php echo in_array($t['id'], $tagFilterIds) ? 'selected' : ''; ?>><?php echo e($t['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button class="btn" type="submit">Filtrer</button>
                            <?php if($q !== '' || $categoryFilter > 0 || !empty($tagFilterIds)): ?><a href="list.php" class="quick-link">Réinitialiser</a><?php endif; ?>
                        </form>

                        <a class="btn add-btn" href="create.php">+ Ajouter un article</a>
                    </div>
                </header>

                    <?php if (empty($articles)): ?>
                        <p class="small">Aucun article pour le moment.</p>
                    <?php else: ?>
                        

                        <div class="article-grid">
                            <?php foreach ($articles as $a):
                                $tags = isset($tagsMap[$a['id']]) ? $tagsMap[$a['id']] : [];
                                $text = strip_tags($a['content']);
                                $excerpt = mb_strlen($text) > 220 ? mb_substr($text,0,220) . '…' : $text;
                            ?>
                                <article class="article-card" onclick="window.location.href='view.php?id=<?php echo $a['id']; ?>';">
                                    <?php if (!empty($a['image_url'])): ?>
                                        <div class="thumb"><img src="../../<?php echo e($a['image_url']); ?>" alt=""></div>
                                    <?php else: ?>
                                        <div class="thumb thumb--empty">Pas d'image</div>
                                    <?php endif; ?>

                                    <div class="body">
                                        <h3><?php echo e($a['title']); ?></h3>
                                        <div class="slug"><?php echo e($a['slug']); ?></div>
                                        <div class="excerpt"><?php echo e($excerpt); ?></div>

                                        <div class="meta">
                                            <div style="color:var(--muted)">Catégorie: <strong><?php echo e($a['category_name'] ?? '—'); ?></strong></div>
                                            <div class="chips">
                                                <?php foreach ($tags as $t): ?>
                                                    <span class="chip"><?php echo e($t['name']); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                                            <div>
                                                <a class="btn btn-ghost btn-sm" href="edit.php?id=<?php echo $a['id']; ?>" onclick="event.stopPropagation()">Éditer</a>
                                                <form method="post" action="delete.php" onsubmit="return confirm('Supprimer cet article ?');" style="display:inline">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                                    <button type="submit" onclick="event.stopPropagation()" class="btn btn-ghost btn-danger" style="margin-left:10px">Supprimer</button>
                                                </form>
                                            </div>
                                            <div style="color:var(--muted);font-size:.9rem"><?php echo e($a['created_at']); ?></div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($totalPages > 1): ?>
                            <?php
                                $baseQuery = $_GET;
                                unset($baseQuery['page']);
                                $baseQueryStr = http_build_query($baseQuery);
                            ?>
                            <nav style="margin-top:18px;text-align:center">
                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <strong style="margin:0 6px"><?php echo $p; ?></strong>
                                    <?php else: ?>
                                        <a href="?<?php echo $baseQueryStr ? $baseQueryStr . '&' : ''; ?>page=<?php echo $p; ?>" style="margin:0 6px"><?php echo $p; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
                <?php require_once __DIR__ . '/../_footer.php'; ?>
            </main>
        </div>
    </div>
</body>
</html>
