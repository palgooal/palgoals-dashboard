jQuery(document).ready(function ($) {
    $('#add-category-form').on('submit', function (e) {
        e.preventDefault();

        const data = {
            action: 'palgoals_add_menu_category', // نفس اسم الـ AJAX action
            nonce: palgoals_dashboard.nonce,
            name: $('#category-name').val(),
            slug: $('#category-slug').val(),
            parent: $('#parent-category').val(),
            description: $('#category-description').val(),
            image_id: $('#category_image').val(),
        };

        $.ajax({
            url: palgoals_dashboard.ajaxurl, // يتم تعريفه بواسطة wp_localize_script
            method: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('Category added: ' + response.data.term_id);
                    location.reload(); // تحديث الصفحة إذا لزم الأمر
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function () {
                alert('Failed to send request.');
            },
        });
    });

    // رفع الصور (نفس الكود الذي تستخدمه لاختيار الصورة)
    $(document).on('click', '#upload-category-image', function (e) {
        e.preventDefault();
        let mediaUploader = wp.media({
            title: 'Choose Image',
            button: { text: 'Use this image' },
            multiple: false,
        });

        mediaUploader.on('select', function () {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#category_image').val(attachment.id);
            $('#category-image-preview').html(`<img src="${attachment.url}" style="max-width: 150px; height: auto;">`);
        });

        mediaUploader.open();
    });
});