<?php
// small helper: determine admin base URL relative to web root
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$base = (basename($scriptDir) === 'admin') ? $scriptDir : dirname($scriptDir);

?>
<link rel="stylesheet" href="/assets/css/admin.patch.css">
<aside class="sidebar" aria-label="Navigation administrateur">
    <div class="sidebar-brand"><a href="<?php echo $base; ?>/dashboard.php">NewsIran</a></div>
    <nav class="sidebar-nav" role="navigation" aria-label="Principale">
        <ul>
            <li>
                <a href="<?php echo $base; ?>/dashboard.php">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3 11.5L12 4l9 7.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 21V12h6v9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Tableau de bord
                </a>
            </li>

            <li>
                <a href="<?php echo $base; ?>/articles/list.php">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Articles
                </a>
            </li>

            <li>
                <a href="<?php echo $base; ?>/categories/list.php">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Catégories
                </a>
            </li>

            <li>
                <a href="<?php echo $base; ?>/tags/list.php">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20.59 13.41L11 3.83 3.83 11 13.41 20.59a2 2 0 0 0 2.83 0l1.35-1.35a2 2 0 0 0 0-2.83z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 7h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Tags
                </a>
            </li>

            <li>
                <a href="<?php echo $base; ?>/logout.php" class="logout-link">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12H9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13 19H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Se déconnecter
                </a>
            </li>
        </ul>
    </nav>
</aside>
