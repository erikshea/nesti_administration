<div class="container">
    <div class="row justify-content-center">
        <div class="col-9  col-md-8 col-lg-7  ">

            <div class="login-form">
<?php if (@$vars['message'] == "invalid"): ?>
                <div class="alert alert-danger text-center p-3" role="alert">
                    Vos identifiants sont incorrects.
                </div>
<?php endif ?>
<?php if (@$vars['message'] == "disconnect"): ?>
                <div class="alert alert-success text-center p-3" role="alert">
                    Déconnexion réussie.
                </div>
<?php endif ?>
                <form action="<?=$vars["baseUrl"]?>user/login" class="mt-4" method="post">
                    <h3 class="text-center">Connexion</h3>       
<?php $vars['formBuilder']->add('login',    ['validation' => false]) 
                          ->add('password', ['validation' => false]) ?>
                    <div class="row justify-content-end">
                    <div class="form-group ">
                        <button type="submit" class="btn btn-info px-4 mr-2">Valider</button>
                    </div>   
                    </row>
                </form>
            </div>
        </div>
    </div>
</div>