<div class="container mt-3">
    <div class="row">
        <div class="col-6 col-md-5">
            <h2>Commandes</h2>
            <div id="chartOrder"></div>
        </div>
        <div class="col-6 col-md-4">
            <h2>Consultation du site</h2>
            <div id="chartConnexionLog"></div>
        </div>
        
        <div class="col-6 col-md-3">
            <h4>Top 10 utilisateurs</h4>
            <div class="primary-border">
    <?php foreach ($vars['usersWithMostConnections'] as $user): ?>
                <div class="d-flex justify-content-between">
                    <span><?= $user->getFirstName() . " " . $user->getLastName()  ?></span>
                    <a class="seeBtn" href="<?= $vars['baseUrl'] ?>users/edit/<?= $user->getId() ?>">Voir</a>
                </div>
    <?php endforeach ?>
            </div>
        </div>

        <div class="col-6">
            <h4>Plus grosses commandes</h4>
            <div class="primary-border">
                <?php foreach ($vars['ordersByTotal'] as $order): ?>
                <div class="d-flex justify-content-between">
                    <span>Commande n° <?= $order->getId() ?></span>
                    <a class="seeBtn" href="<?= $vars['baseUrl'] ?>article/order/<?= $order->getId() ?>">Voir</a>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">
            <h2 class="mt-3">Recettes</h2>

            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <h4>Top 10 Chefs</h4>
                        <div class="primary-border">
                            <?php foreach ($vars["chefsByRecipe"] as $chef): ?>
                            <div class="d-flex justify-content-between">
                                <span><?= $chef->getLastName() . " " . $chef->getFirstName() ?></span>
                                <a class="seeBtn" href="<?= $vars['baseUrl'] ?>user/edit/<?= $chef->getId() ?>">Voir</a>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-7">
                        <h4>Top 10 recettes</h4>
                        <div class="primary-border">
                            <?php foreach ($vars["recipesByGrade"] as $recipe): ?>
                            <div class="d-flex justify-content-between">
                                <a class="seeBtn" href="<?= $vars['baseUrl'] ?>recipe/edit/<?= $recipe->getId() ?>"><?= $recipe->getName() ?></a>
                                <span> par <?= $recipe->getChef()->getLastName() . $recipe->getChef()->getFirstName() ?></span>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <h2>Articles</h2>
            <div id="chartArticle"></div>

            <h4 class="mt-3">En rupture de stock</h4>      
            <table class="table listing">
                    <thead>
                        <tr>
                            <th class="align-middle" scope="col">Nom</th>
                            <th class="align-middle" scope="col">Quantité vendue</th>
                            <th class="align-middle" scope="col">Bénéfice (€)</th>
                            <th class="align-middle" scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($vars["articlesOutOfStock"] as $article): ?>
                            <tr>
                                <td class="align-middle"><?= $article->getProduct()->getName() ?></td>
                                <td class="align-middle"><?= $article->getQuantitySold() ?></td>
                                <td class="align-middle"><?= $article->getTotalSales() - $article->getTotalPurchases()?></td>
                                <td class="align-middle"><a class="seeBtn" href="<?= $vars['baseUrl'] ?>article/edit/<?= $article->getId() ?>">voir</a></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
            </table>
        </div>
    </div>
</div> 
