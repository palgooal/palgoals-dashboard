jQuery(document).ready(function ($) {
    $('#upload-food-image').click(function (e) {
        e.preventDefault(); // منع السلوك الافتراضي للزر

        // فتح مكتبة الوسائط
        var mediaUploader = wp.media({
            title: 'اختر صورة',
            button: {
                text: 'استخدام هذه الصورة'
            },
            multiple: false // للسماح برفع صورة واحدة فقط
        });

        // تحديد الصورة بعد الاختيار
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#Food-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%;">'); // عرض الصورة
            $('#food_image').val(attachment.id); // حفظ معرف الصورة
        });

        mediaUploader.open(); // فتح مكتبة الوسائط
    });
});
