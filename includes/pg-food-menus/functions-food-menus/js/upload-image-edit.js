jQuery(document).ready(function($) {
    let mediaUploader;

    $('#upload-food-image').on('click', function(e) {
        e.preventDefault();

        // فتح مكتبة الوسائط إذا لم تكن مفتوحة بالفعل
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // إنشاء مكتبة الوسائط
        mediaUploader = wp.media({
            title: 'Select Food Image',
            button: {
                text: 'Use this image'
            },
            multiple: false // السماح باختيار صورة واحدة فقط
        });

        // عند اختيار الصورة
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();

            // تحديث المعاينة
            if (attachment.url) {
                $('#food-image-preview').html('<img src="' + attachment.url + '" class="img-thumbnail" alt="Preview Image" />');
            } else {
                $('#food-image-preview').html('<p>' + 'No image selected' + '</p>');
            }

            // تحديث الحقل المخفي بمعرف الصورة
            $('#food_image').val(attachment.id);
        });

        mediaUploader.open();
    });
});
