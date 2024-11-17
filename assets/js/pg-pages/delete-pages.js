jQuery(document).ready(function($) {
    $('.delete-page').off('click').on('click', function(e) {
        e.preventDefault();

        var pageId = $(this).data('page-id');

        if (confirm('هل أنت متأكد من أنك تريد حذف هذه الصفحة؟')) {
            $.ajax({
                url: palgoals_delete_pages_object.ajaxurl, // استخدام ajaxurl من PHP
                type: 'POST',
                data: {
                    action: 'palgoals_delete_page', // اسم الإجراء في PHP
                    page_id: pageId,
                    security: palgoals_delete_pages_object.nonce // الـ nonce للحماية
                },
                success: function(response) {
                    if (response.success) {
                        alert('تم حذف الصفحة بنجاح');
                        location.reload(); // تحديث الصفحة بعد الحذف
                    } else {
                        alert('حدث خطأ أثناء حذف الصفحة.');
                    }
                },
                error: function() {
                    alert('تعذر الاتصال بالخادم.');
                }
            });
        }
    });
});