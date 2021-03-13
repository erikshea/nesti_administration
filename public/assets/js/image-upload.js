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
            $("#image-upload__status").val("changed");
            var reader = new FileReader();

            reader.readAsDataURL(input.files[0]);

            reader.onload = (e) => {
                updatePreview('url(' + e.target.result + ')');
            }
        }
    });

    $("#image-upload__delete").click(() => {
        // reset input value (file) while keeping attached event handlers
        $("#image-upload__add").val("").clone(true);
        
        $("#image-upload__status").val("deleted");
        updatePreview(placeHolder);
    });
})