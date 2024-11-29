jQuery(document).ready(function ($) {
    $('.delete-food-item').on('click', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this item?')) {
            return;
        }

        const postId = $(this).data('post-id'); // جلب معرف المنشور
        const nonce = palgoalsData.nonce; // جلب nonce من كائن معرّف مسبقًا

        $.ajax({
            url: palgoalsData.ajax_url, // رابط AJAX
            type: 'POST',
            data: {
                action: 'delete_food_item',
                nonce: nonce,
                post_id: postId
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload(); // إعادة تحميل الصفحة
                } else {
                    alert(response.data.message);
                }
            },
            error: function () {
                alert('An error occurred while deleting the item.');
            }
        });
    });
});
