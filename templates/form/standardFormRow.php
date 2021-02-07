<div class="form-row">
    <label for="<?= $vars['id'] ?>"><?= $vars['label'] ?></label>
    <input id="<?= $vars['id'] ?>"
        class="form-control <?= empty($vars['errorMessages'])?'invalid':'is-invalid' ?>" name="<?= $vars['entityClass'] ?>[<?= $vars['id'] ?>]"
        type="text" placeholder="<?= @$vars['placeholder'] ?>" value="<?= $vars['value'] ?>">
    <?php foreach ($vars['errorMessages'] as $errorMessage) {
        echo "<div class='invalid-feedback'>$errorMessage</div>";
    } ?>
</div>