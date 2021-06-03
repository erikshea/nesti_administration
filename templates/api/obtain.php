<div class="container">
    <div class="row justify-content-center">
        <div class="col-9  col-md-8 col-lg-7  ">

            <div class="base-form">
<?php if (($vars['message'] ?? "") == "invalid"): ?>
                <div class="alert alert-danger text-center p-3" role="alert">
                    Veuillez vérifier les champs de saisie.
                </div>
<?php endif ?>
<?php if (($vars['message'] ?? "") == "success"): ?>
                <div class="alert alert-success text-center p-3" role="alert">
                    Votre jeton: <?=$vars["token"]?>
                </div>
<?php else: ?>
                <form action="<?=$vars["baseUrl"]?>api/obtain" class="mt-4 <?= $vars["isSubmitted"] ? "needs-validation" : "no-validate" ?>" method="post">
                    <h3 class="text-center">Demande de jeton API</h3>       
<?php $vars['formBuilder']->add('name') ?>
                    <div class="row justify-content-end">
                    <div class="form-group ">
                        <button type="submit" class="btn px-4 mr-2">Valider</button>
                    </div>  
                </form>
<?php endif ?>
            </div>
        </div>
    </div>
</div>