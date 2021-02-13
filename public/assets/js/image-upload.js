$ ( () => {
    let preview = $('#image-upload__preview');

    const updatePreview = (property) => {
        preview.css('background-image', property);
        preview.hide();
        preview.fadeIn(500);
    }

    if ( preview.css('background-image') == "none"){
        updatePreview(placeHolder);
    }

    $("#image-upload__add").change((e) => {
        let input = e.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = (e) => {
                updatePreview('url(' + e.target.result + ')');
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $("#image-upload__delete").click(() => {
        $("#image-upload__add").val(null).clone(true);
        updatePreview(placeHolder);
    });
})