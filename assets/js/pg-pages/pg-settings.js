jQuery(document).ready(function ($) {
    $('#save-settings').on('click', function (e) {
        e.preventDefault();

        var formData = new FormData($('#pg-settings-form')[0]);
        formData.append('action', 'save_pg_settings');
        formData.append('security', ajax_object.nonce); // إضافة الـ nonce

        $.ajax({
            url: ajax_object.ajax_url, // استخدام عنوان الـ AJAX الممرر
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#pg-settings-message').hide(); // إخفاء الرسالة قبل عرض رسالة جديدة
                if (response.success) {
                    $('#pg-settings-message')
                        .text(response.data.message)
                        .css('color', 'green')
                        .fadeIn();
                    // إعادة تحميل الصفحة لتطبيق اللغة الجديدة
                    location.reload();
                } else {
                    $('#pg-settings-message')
                        .text(response.data.message)
                        .css('color', 'red')
                        .fadeIn();
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // تسجيل الخطأ للتصحيح
                $('#pg-settings-message').hide(); // إخفاء الرسالة قبل عرض رسالة جديدة
                $('#pg-settings-message')
                    .text('An unexpected error occurred. Please try again.')
                    .css('color', 'red')
                    .fadeIn();
            }
        });
    });
});
