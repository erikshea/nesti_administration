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
    action="<?= $vars["baseUrl"] ?>article/edit/<?= $vars["entity"]->getId() ?>"
    method="post"
    enctype="multipart/form-data">

    <div class="row justify-content-between">
        <div class="col-6 ">
            <?php $vars["formBuilder"]->add("factoryName")
                ->add("displayName")
                ->add("idArticle", ['class' => 'form-row--horizontal'])
                ->add("sellingPrice", ['class' => 'form-row--horizontal'])
                ->add("stock", ['class' => 'form-row--horizontal']); ?>
            <div class="form-group ">
                <a class="btn px-4 mr-2" href="<?= $vars["baseUrl"] ?>recipe">Annuler</a>
                <button type="submit" class="btn btn-success px-4 mr-2">Valider</button>
            </div>
        </div>

        <div class="col-6 d-flex justify-content-center flex-column image-group">
            <?php $vars["formBuilder"]->add("image", [ "initialBackground" => $vars["imageUrl"] ] ) ?>
        </div>
    </div>
</form>