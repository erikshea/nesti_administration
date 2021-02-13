<form class="container <?= $vars["isSubmitted"] ? "" : "no-validate" ?>" action="<?= $vars["baseUrl"] ?>recipe/edit" method="post">
    <div class="row justify-content-between">
        <div class="col-6">

            <?php $vars["formBuilder"]->add("name")
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


    <div class="container">
        <div class="row">
            <div class="col-7 d-flex flex-column">
                <h2>Préparations</h2>
                <div id="recipe__paragraph-list">
                </div>
            </div>
            <div class="col-5">
                
            </div>
        </div>
    </div>

</form>