<div class="container">
    <div class="row justify-content-between">
        <div class="col-5 col-md-4 col-lg-3">
            <form action="<?=$vars["baseUrl"]?>user/list" method="post">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input value="<?= $_POST['search']['name'] ?? "" ?>"
                            type="text" name="search" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

        <div class="col-4 col-md-3 d-flex justify-content-end">
            <a href="<?= $vars['baseUrl'] ?>user/add" class="btn btn-light">
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
                    <th scope="col">Rôle</th>
                    <th scope="col">Dernière connexion</th>
                    <th scope="col">État</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['entities'] as $user): ?>
                    <tr>
                        <th scope="row"><?= $user->getId() ?></th>
                        <td><?= $user->getFullName() ?></td>
                        <td><?= implode(' , ',  TranslateUtil::translateArray($user->getRoles(), "Users") ) ?></td>
                        <td><?= TranslateUtil::translateDate($user->getLatestConnectionDate()) ?></td>
                        <td><?= TranslateUtil::translate($user->getFlag(), "Users") ?></td>
                        <td>
                            <div class="listing__actions">
                                <a href="<?= $vars['baseUrl'] . "user/edit/" . $user->getId() ?>">Modifier</a>
                                <a href="#" onclick="ReactDOM.render(
                                    React.createElement(DeleteModal, {elementName: `<?= $user->getFullName() ?>`,
                                                                          confirm: 'user/delete/<?= $user->getId() ?>'}),
                                    document.getElementById('delete-modal') )">
                                    Supprimer
                                </a>
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