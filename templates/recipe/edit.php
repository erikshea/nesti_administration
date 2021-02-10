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

            <div class="image-upload">
                <div class="image-upload__edit d-flex">
                    <div >
                        <input type='file' id="image-upload__add" accept=".png, .jpg, .jpeg" />
                        <label for="image-upload__add" class="btn btn-sm btn-success"><i class="fas fa-retweet"></i>Changer</label>
                    </div>
                    <a href="#" id="image-upload__delete" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>
                </div>
                <div class="image-upload__content">
                    <div id="image-upload__preview" style="background-image: url(<?=
                        $vars["assetsUrl"] . "/images/content/"
                            .   ($vars['entity']->getImage() != null ?
                                $vars['entity']->getImage()->getFileName()
                                : "__placeHolder.jpg")
                        ?>);">
                    </div>
                </div>

            </div>

        </div>
        <div class="filename image-group__button-bar"><?= $vars['entity']->getImage()->getFileName() ?><a><i class="far fa-trash-alt"></i></a></div>
    </div>


    <div class="container">


    </div>

</form>