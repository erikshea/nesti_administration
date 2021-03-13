<div class="row <?= $vars['class'] ?>" id="indicated-form-field">
</div>
<script>
    var passwordProps = {
        "label": "<?= $vars['label'] ?>",
        "name": "<?="{$vars['formName']}[{$vars['fieldName']}]" ?>"
    };
</script>

<script src='<?= "{$vars['assetsUrl']}js/PasswordInput.js?version={$vars['version']}" ?>' type='text/babel'></script>
