<div class="container">
<div class="row">
    <div class="col-3">
        <form>
          
        </form>
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