<nav id="main-menu" class="d-flex justify-content-between ">
    <div id="main-menu__pages" class="navbar navbar-expand-sm ">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsable-buttons" aria-controls="collapsable-buttons" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div id="collapsable-buttons" class="collapse navbar-collapse">
            <div class="navbar-nav w-100 d-flex justify-content-between py-3 px-lg-3">
                <div class="nav-item dropdown">
                    <a class="nav-link <?= $vars['route']['controller']=='recipe' ? "active":"" ?>"
                        id="menu-item-recipes" href="<?= $vars['baseUrl'] ?>recipe" >
                        <i class="fas fa-clipboard-list"></i>
                        <span>Recettes</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $vars['route']['controller']=='article' ? "active":"" ?>"
                        id="menu-item-articles" href="<?= $vars['baseUrl'] ?>article">
                        <i class="fas fa-utensils"></i>
                        <span>Articles</span>
                    </a>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link <?= $vars['route']['controller']=='user' ? "active":"" ?>"
                        id="menu-item-users" href="<?= $vars['baseUrl'] ?>user" >
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                </div>

                <a class="nav-item nav-link <?= $vars['route']['controller']=='statistics' ? "active":"" ?>"
                    href="<?= $vars['baseUrl'] ?>statistics">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
            </div>
        </div>
    </div>

    <div id="main-menu__user" class="navbar">
        <a class="nav-item nav-link"
            aria-label="Profil"
            href="<?= $vars['baseUrl'] ?>user/edit/<?= $vars['currentUser']->getId() ?>">
            <i class="fas fa-user"></i>
            <span><?= $vars['currentUser']->getFullName() ?></span>
        </a>
        <a class="nav-item nav-link" href="<?= $vars['baseUrl'] ?>user/logout"
            aria-label="Déconnexion">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</nav>