<nav id="main-menu" class="d-flex justify-content-between">
    <div id="main-menu__pages" class="navbar navbar-expand-sm ">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsable-buttons" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div id="collapsable-buttons" class="collapse navbar-collapse">
            <div class="navbar-nav w-100 d-flex justify-content-between py-3 px-lg-3">
                <div class="nav-item dropdown">
                    <a class="nav-link" id="menu-item-recipes" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Recettes</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menu-item-recipes">
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>recipe/list">Liste</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>recipe/create">Création</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>recipe/edit">Édition</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link" id="menu-item-articles" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-utensils"></i>
                        <span>Articles</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menu-item-articles">
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>article/list">Liste</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>article/orders">Commandes</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>article/import">Importation</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>article/edit">Édition</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link" id="menu-item-users" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menu-item-users">
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>user/list">Liste</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>user/create">Création</a>
                        <a class="dropdown-item" href="<?= $vars['baseUrl'] ?>user/edit">Édition</a>
                    </div>
                </div>

                <a class="nav-item nav-link" href="<?= $vars['baseUrl'] ?>statistics">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
            </div>
        </div>
    </div>

    <div id="main-menu__user" class="navbar">
        <a class="nav-item nav-link" href="<?= $vars['baseUrl'] ?>user/edit/<?= $vars['currentUser']->getId() ?>">
            <i class="fas fa-user"></i>
            <span><?= $vars['currentUser']->getFullName() ?></span>
        </a>
        <a class="nav-item nav-link" href="<?= $vars['baseUrl'] ?>user/logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</nav>