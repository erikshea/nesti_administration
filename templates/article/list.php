<div class="container">
    <div class="row justify-content-between">
        <div class="col-5 col-md-4 col-lg-3">
            <form action="<?=$vars["baseUrl"]?>article/list" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input value="<?= $_POST['search']['name'] ?? "" ?>"
                            type="text" name="search[name]" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

        <div class="col-7 d-flex justify-content-end">
            <a href="<?= $vars['baseUrl'] ?>article/orders" class="btn btn-secondary mr-4 ">
            <i class="far fa-eye"></i><span>Commandes</span>
            </a>
            <a href="<?= $vars['baseUrl'] ?>article/import" class="btn btn-light">
                <i class="fas fa-plus-circle"></i><span>Importer</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table listing">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix de vente</th>
                    <th scope="col">Dernière importation</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['entities'] as $article): ?>
                    <tr>
                        <th scope="row"><?= $article->getId() ?></th>
                        <td><?= $article->getProduct()->getName() ?></td>
                        <td><?= $article->getSellingPrice() ?></td>
                        <td><?= $article->getLastImportationDate() ?></td>
                        <td><?= $article->getStock() ?></td>
                        <td>
                            <div class="listing__actions">
                                <a href="<?= $vars['baseUrl'] . "article/edit/" . $article->getId() ?>">Modifier</a>
                                <a href="#" onclick="ReactDOM.render(
                                    React.createElement(DeleteModal, {elementName: `<?= $article->getProduct()->getName() ?>`,
                                                                          confirm: 'article/delete/<?= $article->getId() ?>'}),
                                    document.getElementById('delete-modal') )">Supprimer</a>
                            </div>
                        </td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="delete-modal"></div>