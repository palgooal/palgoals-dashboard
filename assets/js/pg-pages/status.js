jQuery(document).ready(function($) {
    $('.toggle-status').on('click', function(e) {
        e.preventDefault();
        
        var pageId = $(this).data('page-id');
        var currentStatus = $(this).text().trim(); // احصل على الحالة الحالية من النص

        // قم بتحديد الحالة الجديدة بناءً على الحالة الحالية
        var newStatus = currentStatus === 'Publish' ? 'draft' : 'publish';

        console.log('Sending AJAX request with Page ID:', pageId, 'New Status:', newStatus); // إضافة سجل

        $.ajax({
            url: ajaxurl, // URL الخاصة بـ AJAX
            type: 'POST',
            data: {
                action: 'toggle_page_status',
                page_id: pageId,
                new_status: newStatus // استخدم الحالة الجديدة
            },
            success: function(response) {
                console.log(response); // إضافة سجل للرد
                // تحديث حالة الصفحة في الواجهة
                if (response.success) {
                    // تحديث النص ولون الزر بناءً على الحالة الجديدة
                    $(this).text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1)); // تحديث نص الزر
                    // تغيير اللون حسب الحالة (اختياري)
                    $(this).css('background-color', newStatus === 'publish' ? 'green' : 'red');
                    location.reload(); // إعادة تحميل الصفحة لتحديث الحالة
                } else {
                    alert(response.data);
                }
            }.bind(this) // تأكد من الحفاظ على سياق `this`
        });
    });
});


