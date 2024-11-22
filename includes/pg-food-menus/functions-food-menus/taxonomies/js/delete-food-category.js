jQuery(document).ready(function ($) {
    // Delete Category
    $('.delete-category').click(function (e) {
        e.preventDefault();

        if (!confirm(deleteCategoryAjax.confirmMessage)) {
            return;
        }

        var categoryId = $(this).data('category-id');

        console.log({
            action: 'delete_food_menu_category',
            category_id: categoryId,
            nonce: deleteCategoryAjax.nonce,
        }); // Debugging Log

        $.ajax({
            url: deleteCategoryAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_food_menu_category',
                category_id: categoryId,
                nonce: deleteCategoryAjax.nonce,
            },
            success: function (response) {
                console.log(response); // Debugging Log
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                alert(deleteCategoryAjax.errorMessage);
            },
        });
    });
});
