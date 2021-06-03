
<div class="container">
    <div class="row">
        <div class="col">
<?php if (($vars['message'] ?? null) == "edited"): ?>
            <div class="alert alert-success text-center p-3" role="alert">
                Vos modifications ont été enregistrées.
            </div>
<?php endif ?>
<?php if (($vars['message'] ?? null) == "created"): ?>
            <div class="alert alert-success text-center p-3" role="alert">
                Votre recette a été créée.
            </div>
<?php endif ?>
<?php if (($vars['message'] ?? null) == "error"): ?>
            <div class="alert alert-danger text-center p-3" role="alert">
                Veuillez vérifier vos informations.
            </div>
<?php endif ?>
        </div>
    </div>
    </div>
<form
    class="container <?= $vars["isSubmitted"] ? "needs-validation" : "no-validate" ?>"
    action="<?= $vars["baseUrl"] ?>recipe/edit/<?= $vars["entity"]->getId() ?>"
    method="post"
    enctype="multipart/form-data">

    <div class="row justify-content-between">
        <div class="col-6">

            <?php $vars["formBuilder"]
                ->add("csrf")
                ->add("name")
                ->add("difficulty", ['class' => 'form-row--horizontal'])
                ->add("portions", ['class' => 'form-row--horizontal'])
                ->add("preparationTime", ['class' => 'form-row--horizontal']); ?>
            <div class="form-group ">
                <button type="submit" class="btn btn-success px-4 mr-2">Valider</button>
                <a class="btn px-4 mr-2" href="<?= $vars["baseUrl"] ?>recipe">Annuler</a>
            </div>
        </div>

        <div class="col-6 d-flex justify-content-center flex-column image-group">
            <?php $vars["formBuilder"]->add("image", [ "initialBackground" => $vars["imageUrl"] ] ) ?>
        </div>
    </div>
</form>
<?php if ($vars['entity']->existsInDataSource()): ?>
<div class="container">
    <div class="row">
        <div class="col-7 d-flex flex-column">
            <h2>Préparations</h2>
            <div id="recipe__paragraph-list">
            </div>
        </div>
        <div class="col-5">
            <h2>Liste des ingrédients</h2>
            <div id="recipe__ingredients">
            </div>
        </div>
    </div>
</div>
<?php endif ?>
