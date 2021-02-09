<div class="container">
    <div class="row justify-content-between">
        <div class="col-5 col-md-4 col-lg-3">
            <form action="<?=$vars["baseUrl"]?>recipe/list" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input value="<?= @$_POST['search']['name']?>"
                            type="text" name="search[name]" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

        <div class="col-4 col-md-3 d-flex justify-content-end">
            <a href="<?= $vars['baseUrl'] ?>article/add" class="btn btn-light">
                <i class="fas fa-plus-circle"></i><span>Ajouter</span>
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
                    <th scope="col">Difficulté</th>
                    <th scope="col">Pour</th>
                    <th scope="col">Temps</th>
                    <th scope="col">Chef</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['entities'] as $recipe): ?>
                    <tr>
                        <th scope="row"><?= $recipe->getId() ?></th>
                        <td><?= $recipe->getName() ?></td>
                        <td><?= $recipe->getDifficulty() ?></td>
                        <td><?= $recipe->getPortions() ?></td>
                        <td><?= FormatUtil::formatTime($recipe->getPreparationTime()) ?></td>
                        <td><?= $recipe->getChef()->getLastName() ?></td>
                        <td>
                            <div class="listing__actions">
                                <a href="<?= $vars['baseUrl'] . "recipe/edit/" . $recipe->getId() ?>">Modifier</a>
                                <a href="<?= $vars['baseUrl'] . "recipe/delete/" . $recipe->getId() ?>">Supprimer</a>
                            </div>
                        </td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>