<div class="row">
    <div class = "col form-group">
    <label for="<?= $vars['fieldName'] ?>"><?= $vars['label'] ?></label>
    <div class="input-group">
<?php if (isset($vars['icon'])): ?>
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="<?= $vars['icon'] ?>"></i>
            </span>
        </div>
<?php endif ?>
        <input
            id="<?= $vars['fieldName'] ?>"
            class="form-control <?= empty($vars['errorMessages'])?'invalid':'is-invalid' ?>"
            name="<?= "{$vars['formName']}[{$vars['fieldName']}]" ?>"
            type="<?= $vars['type'] ?>"
            value="<?= $vars['value'] ?>"
<?php if ($vars['required']): ?>
            required="required"
            oninvalid="this.setCustomValidity('Ce champ doit être renseigné')"
            oninput="this.setCustomValidity('')"
<?php endif ?>
        >
    </div>
<?php foreach ($vars['errorMessages'] as $errorMessage): ?>
        <div class='invalid-feedback'><?= $errorMessage ?></div>
<?php endforeach ?>
    </div>
</div>
