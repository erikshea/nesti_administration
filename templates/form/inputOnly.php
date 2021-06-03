<input
            id="<?= $vars['fieldName'] ?>"
            name="<?= $vars['inputName'] ?? "{$vars['formName']}[{$vars['fieldName']}]" ?>"
            type="<?= $vars['type'] ?>"
            value="<?= $vars['value'] ?>"
            <?= ($vars['readOnly'] ?? false )?"readonly":""?>
>