jQuery(document).ready(function ($) {
    $('#upload-food-image').on('click', function (e) {
        e.preventDefault();

        // فتح مكتبة الوسائط
        var mediaUploader = wp.media({
            title: 'Choose Category Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            // تحديث الحقل المخفي بقيمة الصورة الجديدة
            $('#category_image').val(attachment.id);
            // تحديث المعاينة بالصورة الجديدة
            $('#food-image-preview').html('<img src="' + attachment.url + '" class="img-thumbnail" />');
        });

        mediaUploader.open();
    });
});
