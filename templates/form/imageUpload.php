
<script>
    let placeHolder = "url('<?= $vars["placeHolder"] ?>')";
</script>
<script src='<?= "{$vars['assetsUrl']}js/image-upload.js?version={$vars['version']}" ?>'></script>

<div class="image-upload">
    <div class="image-upload__edit d-flex">
        <div >
            <input type='file' id="image-upload__add" name="<?= "{$vars['formName']}[{$vars['fieldName']}]" ?>" accept=".png, .jpg, .jpeg" />
            <label for="image-upload__add" class="btn btn-sm btn-success"><i class="fas fa-retweet"></i>Changer</label>
        </div>
        <a href="#" id="image-upload__delete" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>
    </div>
    <div class="image-upload__content">
        <div id="image-upload__preview" style="background-image: <?= $options["initialBackground"] ? "url({$options['initialBackground']})":"none" ?>">
        </div>
    </div>
</div>