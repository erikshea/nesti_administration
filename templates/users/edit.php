<div class="container">
    <div class="row">
        <div class="col">
<?php if (($vars['message'] ?? null) == "edited"): ?>
            <div class="alert alert-success text-center p-3" role="alert">
                Vos modifications ont été enregistrées.
            </div>
<?php endif ?>

<?php if (($vars['message'] ?? null) == "invalid"): ?>
            <div class="alert alert-danger text-center p-3" role="alert">
                Veuillez vérifier vos informations.
            </div>
<?php endif ?>
        </div>
    </div>
</div>
<form
    class="container <?= $vars["isSubmitted"] ? "" : "no-validate" ?>"
    action="<?= $vars["baseUrl"] ?>user/edit/<?= $vars["entity"]->getId() ?>"
    method="post">

    <div class="row justify-content-between">
        <div class="col-6">
            <?php $vars["formBuilder"]
                ->add("firstName")
                ->add("lastName")
                ->add("roles")
                ->add("flag")?>
<?php if ($vars["entity"]->getId() == MainController::getLoggedInUser()->getId()): ?>
    <?php $vars["formBuilder"]
                ->add("password")
                ->add("passwordConfirm")?>
<?php endif ?>

            <div class="form-group ">
                <button type="submit" class="btn btn-success px-4 mr-2">Valider</button>
                <a class="btn px-4 mr-2" href="<?= $vars["baseUrl"] ?>recipe">Annuler</a>
            </div>
        </div>

        <div class="col-6 d-flex flex-column image-group">
            <h2>Informations</h2>
            <ul class="list-group list-group-flush primary-border">
                <li class="list-group-item">
                    <span>Date de création : </span>
                    <span><?= TranslateUtil::translateDate($vars["entity"]->getDateCreation()) ?></span>
                </li>
                <li class="list-group-item">
                    <span>Dernière connexion : </span>
                    <span><?= TranslateUtil::translateDate($vars["entity"]->getLatestConnectionDate()) ?></span>
                </li>
<?php if ($vars['entity']->isChef()): ?>
                <li class="list-group-item">
                    <strong>Chef Patissier : </strong>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Nombre de recettes : </span>
                            <span><?= count($vars["entity"]->getChef()->getRecipes()) ?></span>
                        </li>
                        <li class="list-group-item">
                            <span>Dernière recette : </span>
                            <span>
                                <?=   $vars["entity"]->getChef()->getLatestRecipe() == null
                                        ?"-":$vars["entity"]->getChef()->getLatestRecipe()->getName() ?>
                            </span>
                        </li>
                    </ul>
                </li>
<?php endif ?>
                <li class="list-group-item">
                    <strong>Utilisateur : </strong>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Nombre de commandes : </span>
                            <span><?= count($vars["entity"]->getOrders()) ?></span>
                        </li>
                        <li class="list-group-item">
                            <span>Dernière commande : </span>
                            <span>
                                <?=   $vars["entity"]->getLatestOrder() == null
                                        ?"-":TranslateUtil::translateNumber($vars["entity"]->getLatestOrder()->getTotal()) ?> Euros
                            </span>
                        </li>
                    </ul>
                </li>
<?php if ($vars['entity']->isAdministrator()): ?>
                <li class="list-group-item">
                    <strong>Administrateur : </strong>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Nombre de d'importations faites : </span>
                            <span><?= count($vars["entity"]->getAdministrator()->getImportations()) ?></span>
                        </li>
                        <li class="list-group-item">
                            <span>Dernière importation : </span>
                            <span>
                                <?=   $vars["entity"]->getAdministrator()->getLatestImportation() == null
                                        ?"-":TranslateUtil::translateDate($vars["entity"]->getAdministrator()->getLatestImportation()->getDateImportation()) ?>
                            </span>
                        </li>
                    </ul>
                </li>
<?php endif ?>
<?php if ($vars['entity']->isModerator()): ?>
                <li class="list-group-item">
                    <strong>Modérateur : </strong>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Nombre de de commentaires bloqués : </span>
                            <span><?= count($vars["entity"]->getModerator()->getModeratedComments(["b"])) ?></span>
                        </li>
                        <li class="list-group-item">
                            <span>Nombre de de commentaires approuvés : </span>
                            <span><?= count($vars["entity"]->getModerator()->getModeratedComments(["a"])) ?></span>
                        </li>
                    </ul>
                </li>
<?php endif ?>
            </ul>
        </div>
    </div>
</form>

<?php if ($vars['entity']->isModerator()): ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-7">
            <h2>Ses commandes</h2>
            <table class="table listing orders__table">
                <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Date</th>
                        <th scope="col">Nb d'articles</th>
                        <th scope="col">État</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['entity']->getOrders() as $order): ?>
                    <tr>
                        <td><?= $order->getId() ?></td>
                        <td><?= TranslateUtil::translateNumber($order->getTotal())?>€</td>
                        <td><?= TranslateUtil::translateDate($order->getDateCreation()) ?></td>
                        <td><?= count($order->getOrderLines()) ?></td>
                        <td><?= TranslateUtil::translate($order->getFlag(),"Orders") ?></td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <div id="order-items" class="h-100"></div>
        </div>
    </div>
</div>
<?php endif ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2>Ses commentaires</h2>
            <table class="table listing edit__comments">
                <thead>
                    <tr>
                        <th scope="col">Recette</th>
                        <th scope="col">Contenu</th>
                        <th scope="col">Date</th>
                        <th scope="col">État</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($vars['entity']->getComments() as $comment): ?>
                    <tr>
                        <td><?= $comment->getRecipe()->getName() ?></td>
                        <td><?= $comment->getCommentContent() ?></td>
                        <td><?= TranslateUtil::translateDate($comment->getDateCreation()) ?></td>
                        <td><?= TranslateUtil::translate($comment->getFlag(),"Comment") ?></td>
                        <td>
                            <div class="listing__actions" data-idrecipe="<?= $comment->getIdRecipe() ?>" data-iduser="<?= $comment->getIdUsers() ?>">
                                <a data-block="false" href="javascript:void(0)">Approuver</a>
                                <a data-block="true" href="javascript:void(0)">Bloquer</a>
                            </div>
                        </td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>