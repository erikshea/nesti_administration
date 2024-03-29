<div class="row <?= $vars['class'] ?>">
    <div class = "col form-group">
        <div class="form-group__content">
            <div class="input-group d-flex flex-column"> 
                <label><?= $vars['label'] ?></label>
                <div class="d-flex justify-content-between">
<?php foreach ($vars['choices'] as $i=>$choice): ?>
                    <div class="form-check form-check-inline">
                        <input
                            id="<?= $vars['fieldName'] ?>__choice-<?= $i ?>"
                            class="form-check-input <?= empty($vars['errorMessages'])?'invalid':'is-invalid' ?>"
                            name="<?= "{$vars['formName']}[{$vars['fieldName']}]" . (is_array($vars['checked'])?"[]":"") ?>"
                            type="<?= $vars['type'] ?>"
                            value="<?= $choice ?>"
                            <?= ($vars['readOnly'] ?? false )?"readonly":""?>
                            <?= is_array($vars['checked'])?
                                (in_array( $choice, $vars['checked'])?"checked":""): // If $vars['checked'] is array (for example roles), see if current checkbox value is in it
                                ($choice==$vars['checked']?"checked":"") // Otherwise if value (for example flag), see if value is same as checked 
                            ?>
    <?php if ($vars['required'] && $i == 0): ?>
                            required="required"
                            oninvalid="this.setCustomValidity('Ce champ doit être renseigné')"
                            oninput="this.setCustomValidity('')"
    <?php endif ?>
                        >
                        <label class="form-check-label" for="<?= $vars['fieldName'] ?>__choice-<?= $i ?>"><?= TranslateUtil::translate($choice,$vars['formName'] ) ?></label>
                    </div>
<?php endforeach ?>
                </div>
            </div>
        </div>
<?php foreach ($vars['errorMessages'] as $errorMessage): ?>
        <div class='invalid-feedback d-block'><?= $errorMessage ?></div>
<?php endforeach ?>
    </div>
</div>
