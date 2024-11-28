jQuery(document).ready(function($) {
    $('.edit-food-button').on('click', function() {
        const postId = $(this).data('post-id');

        // تعبئة معرف المنشور في النموذج
        $('#edit-food-form #post_id').val(postId);

        // استدعاء AJAX لجلب بيانات العنصر
        $.ajax({
            url: ajaxurl, // متغير WordPress AJAX
            type: 'POST',
            data: {
                action: 'get_food_menu_item',
                post_id: postId
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;

                    // تعبئة الحقول بالبيانات المسترجعة
                    $('#food-title').val(data.title);
                    $('#food-description').val(data.description);
                    $('#food-Price').val(data.price);
                    $('#parent-category').val(data.category);
                    $('#food-image-preview').html(
                        `<img src="${data.image_url}" alt="Food Image" style="max-width: 100%;" />`
                    );
                    $('#food_image').val(data.image_id);
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    // رفع صورة جديدة
    $('#upload-food-image').on('click', function() {
        const fileFrame = wp.media({
            title: '<?php _e('Select Food Image', 'palgoals-core'); ?>',
            button: {
                text: '<?php _e('Use this image', 'palgoals-core'); ?>'
            },
            multiple: false
        });

        fileFrame.on('select', function() {
            const attachment = fileFrame.state().get('selection').first().toJSON();
            $('#food-image-preview').html(`<img src="${attachment.url}" style="max-width: 100%;" />`);
            $('#food_image').val(attachment.id);
        });

        fileFrame.open();
    });

    // إرسال البيانات المحدثة
    $('#edit-food-form').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_food_menu_item',
                data: formData
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php _e('Changes saved successfully.', 'palgoals-core'); ?>');
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
});
