$ ( () => {
    let preview = $('#image-upload__preview');

    function updatePreview(property){
        preview.css('background-image', property);
        preview.hide();
        preview.fadeIn(650);
    }

    $("#image-upload__add").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                updatePreview('url('+e.target.result +')');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    $("#image-upload__delete").click(function() {
        $("#image-upload__add").val(null).clone(true);
        updatePreview(placeHolder);
    });
})