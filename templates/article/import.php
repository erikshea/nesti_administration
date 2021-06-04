<div class="container">
    <div class="row">
        <div class="col">
<?php if (($vars['message'] ?? null) == "edited"): ?>
            <div class="alert alert-success text-center p-3" role="alert">
                Vos modifications ont été enregistrées.
            </div>
<?php endif ?>
        </div>
    </div>
</div>
<form
    class="container <?= $vars["isSubmitted"] ? "" : "no-validate" ?>"
    action="<?= $vars["baseUrl"] ?>article/import"
    method="post"
    enctype="multipart/form-data">
    <div class="row justify-content-between">
        <div class="col-12">
            <?php $vars["formBuilder"]
                ->add("csrf")
                ->add("csvFile"); ?>
            <div class="form-group ">
                <button type="submit" class="btn btn-success px-4 mr-2">Valider</button>
                <a class="btn px-4 mr-2" href="<?= $vars["baseUrl"] ?>recipe">Annuler</a>
            </div>
        </div>
    </div>
</form>

<div class="container">
    <div class="row">
        <div class="col">
<?php if (!empty($vars["importedLines"])): ?>
            <h2 class="text-center">Importations réussies</h3>  
            <table class="table listing">
                <thead>
                    <tr>
                    <th scope="col">ID Article</th>
                    <th scope="col">Nom Article</th>
                    <th scope="col">Nom Unité</th>
                    <th scope="col">Prix Article</th>
                    <th scope="col">Numéro de Commande</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['importedLines'] as $line): ?>
                    <tr>
                        <td scope="row"><?= $line["article_idArticle"] ?></td>
                        <td><?= $line["article_name"] ?></td>
                        <td><?= $line["unit_name"] ?></td>
                        <td><?= $line["offers_price"] ?></td>
                        <td><?= $line["orders_number"] ?></td>
                        <td>
                            <div class="listing__actions">
                                <a href="<?= $vars['baseUrl'] . "article/edit/" . $line["article_idArticle"] ?>">Modifier</a>
                            </div>
                        </td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
<?php endif ?>
<?php if (!empty($vars["erroredLines"])): ?>
            <h2 class="text-center">Importations erronées</h3>  
            <table class="table listing">
                <thead>
                    <tr>
                    <th scope="col">ID Article</th>
                    <th scope="col">Nom Article</th>
                    <th scope="col">Nom Unité</th>
                    <th scope="col">Prix Article</th>
                    <th scope="col">Numéro de Commande</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['erroredLines'] as $line): ?>
                    <tr>
                        <td scope="row"><?= $line["article_idArticle"] ?></td>
                        <td><?= $line["article_name"] ?></td>
                        <td><?= $line["unit_name"] ?></td>
                        <td><?= $line["offers_price"] ?></td>
                        <td><?= $line["orders_number"] ?></td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
<?php endif ?>
        </div>
    </div>
</div>