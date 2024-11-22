jQuery(document).ready(function ($) {
    // عند فتح مكتبة الوسائط لاختيار صورة
    $(document).on('click', '#upload-category-image', function (e) {
        e.preventDefault();
        let mediaUploader = wp.media({
            title: 'Choose Image',
            button: { text: 'Use this image' },
            multiple: false,
        });

        mediaUploader.on('select', function () {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#category_image').val(attachment.id); // تحديث الـ ID
            $('#category-image-preview').html(`<img src="${attachment.url}" style="max-width: 150px; height: auto;">`);
        });

        mediaUploader.open();
    });

    // إزالة الصورة
    $(document).on('click', '#remove-category-image', function (e) {
        e.preventDefault();
        $('#category_image').val('');
        $('#category-image-preview').html('');
    });

    // إعادة تعيين الحقول بعد الإضافة
    $('#addtag').on('submit', function () {
        // إعادة تعيين حقل الصورة بعد الإرسال بنجاح
        setTimeout(function () {
            $('#category_image').val(''); // إزالة الـ ID
            $('#category-image-preview').html(''); // إزالة معاينة الصورة
        }, 500); // تأخير بسيط لتجنب أي تعارض مع عملية الإرسال
    });
});
