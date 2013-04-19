(function ($) {
    $('[data-image]').click().change(function () {
        if (this.files && this.files[0]) {
            var img_data = $(this).data('image');
            var reader = new FileReader();
            reader.onload = function (e) {
                $('[data-image="'+img_data+'"]').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
         }
     });
}(jQuery));
