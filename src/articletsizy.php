<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NewsIran - Articles</title>
  <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

  <nav class="navbar" role="navigation" aria-label="Navigation principale">
    <div class="container nav-content">
      <a class="brand" href="index.php">NewsIran</a>
      <div class="nav-actions">
        
      </div>
      <button class="hamburger" aria-label="Basculer la navigation" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </nav>

  <main class="container articles-page">
    <h1>Articles</h1>
    <div class="articles-list">
      <article class="article-card">
        <div class="article-thumb"></div>
        <div class="article-body">
          <h3><a href="#">Analyse : Titre exemple 1</a></h3>
          <div class="meta">Par la rédaction • 2 mars 2026</div>
          <p>Brève introduction à l'article. Cliquez pour en savoir plus.</p>
        </div>
      </article>

      <article class="article-card">
        <div class="article-thumb" style="background:linear-gradient(135deg,#b8ffd6,#57d9a3);"></div>
        <div class="article-body">
          <h3><a href="#">Reportage : Titre exemple 2</a></h3>
          <div class="meta">Par Anna • 28 février 2026</div>
          <p>Un aperçu du reportage terrain et des témoignages recueillis par notre équipe.</p>
        </div>
      </article>

      <article class="article-card">
        <div class="article-thumb" style="background:linear-gradient(135deg,#ffd6a5,#ff7a59);"></div>
        <div class="article-body">
          <h3><a href="#">Dossier : Titre exemple 3</a></h3>
          <div class="meta">Par l'équipe • 15 février 2026</div>
          <p>Une vue d'ensemble sur les grandes questions politiques et sociales.</p>
        </div>
      </article>

    </div>
  </main>

  <footer class="site-footer">
    <div class="container">© <?php echo date('Y'); ?> NewsIran. Tous droits réservés.</div>
  </footer>

  <script>
    const hamb = document.querySelector('.hamburger');
    const navActions = document.querySelector('.nav-actions');
    if (hamb) {
      hamb.addEventListener('click', () => {
        const expanded = hamb.getAttribute('aria-expanded') === 'true';
        hamb.setAttribute('aria-expanded', String(!expanded));
        navActions.classList.toggle('open');
      });
    }
  </script>

</body>
</html>
