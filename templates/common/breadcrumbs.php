<div class="container mt-3 mb-3">
    <div class="row">
        <div class="col">
            <div class="breadcrumbs">
                <a href="<?= $vars['baseUrl'].$vars["route"]["controller"]?>"><span><?= $vars["breadcrumbs"][0] ?></span></a>

<?php if ( isset($vars["breadcrumbs"][1]) ): ?>
                <span><?= $vars["breadcrumbs"][1] ?></span>
<?php endif ?>
            </div>
        </div>
    </div>
</div>