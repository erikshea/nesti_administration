<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-9 col-md-8 col-lg-7 d-flex align-items-center flex-column mt-4">
<?php if (($vars['message'] ?? "") == "invalid"): ?>
            <div class="alert alert-danger text-center p-3" role="alert">
                Veuillez vérifier les champs de saisie.
            </div>
<?php endif ?>
<?php if (($vars['message'] ?? "") == "success"): ?>
            <div class="alert alert-success text-center p-3" role="alert">
                <span>Votre jeton:</span>
                <strong id="api_token"><?=$vars["token"]?></strong>
            </div>
            <button id="api_copy" class="btn btn-sm btn-success width-content"
                onclick="copyApiToken()"
                >
                <i class="fas fa-clipboard"></i>
                <span>Copier dans le presse-papier</span>
            </button>
<?php else: ?>
            <div class="base-form">
                <form action="<?=$vars["baseUrl"]?>api/obtain" class="<?= $vars["isSubmitted"] ? "needs-validation" : "no-validate" ?>" method="post">
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

<script>
    function copyApiToken() {
        const cb = navigator.clipboard;
        const token = document.querySelector('#api_token');
        cb.writeText(token.innerText);
        const button = document.querySelector('#api_copy');
        button.innerHTML = "<i class='fas fa-check'></i> <span>Votre jeton a été copié</span>";
    }
</script>