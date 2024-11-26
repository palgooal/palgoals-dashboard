jQuery(document).ready(function ($) {
    $('#create-page').click(function (e) {
        e.preventDefault(); // منع إعادة تحميل الصفحة

        let data = {
            action: 'add_food_item', // اسم الإجراء
            nonce: ajax_object.nonce, // أمان
            food_title: $('#food-title').val(),
            food_description: $('#food-description').val(),
            food_price: $('#food-Price').val(),
            parent_category: $('#parent-category').val(),
            food_image: $('#food_image').val(),
        };

        $.ajax({
            url: ajax_object.ajax_url, // رابط AJAX
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('تمت إضافة العنصر بنجاح!');
                    location.reload(); // إعادة تحميل الصفحة بعد الإضافة
                } else {
                    alert('خطأ: ' + response.data.message);
                }
            },
            error: function () {
                alert('حدث خطأ في الاتصال بالخادم.');
            },
        });
    });
});
