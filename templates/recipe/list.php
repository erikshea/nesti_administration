<div class="container">
    <div class="row">
        <div class="col">
            <h1>Recettes</h1>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col-5 col-md-4 col-lg-3">
            <form>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                        <input type="text" class="form-control" id="searchInput" >
                    </div>
                </div>
            </form>
        </div>

        <div class="col-4 col-md-3 d-flex justify-content-end">
            <a href="<?= $vars['baseUrl'] ?>article/add" class="btn btn-light">
                <i class="fas fa-plus-circle"></i><span>Ajouter</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
<?php foreach ($vars['entities'] as $recipe): ?>
    <?= $recipe->getName() ?>
<?php endforeach ?>
        </div>
    </div>
</div>