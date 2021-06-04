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
    action="<?= $vars["baseUrl"] ?>user/add"
    method="post">

    <div class="row justify-content-between">
        <div class="col-6">
            <?php $vars["formBuilder"]
                ->add("csrf")
                ->add("firstName")
                ->add("lastName")
                ->add("roles")?>
            <div class="form-group ">
                <button type="submit" class="btn btn-success px-4 mr-2">Valider</button>
                <a class="btn px-4 mr-2" href="<?= $vars["baseUrl"] ?>recipe">Annuler</a>
            </div>
        </div>

        <div class="col-6 d-flex justify-content-center flex-column image-group">
            <?php $vars["formBuilder"]
                ->add("email")
                ->add("login")
                ->add("password")?>
            </div>
    </div>
</form>