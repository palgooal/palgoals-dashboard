jQuery(document).ready(function ($) {
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        // جمع القيم من الحقول
        var email = $('#email').val();
        var password = $('#password').val();
        var nonce = $('#login_nonce').val(); // Nonce للحماية

        // إخفاء الرسائل السابقة
        $('#login-error').addClass('hidden').text('');
        $('#login-success').addClass('hidden').text('');

        // التحقق من إدخال الحقول
        if (!email || !password) {
            $('#login-error').removeClass('hidden').text('Both email and password are required.');
            return;
        }

        // إرسال الطلب باستخدام AJAX
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_login_action', // يجب أن يتطابق مع اسم الدالة في PHP
                email: email,
                password: password,
                security: nonce // إرسال Nonce مع الطلب
            },
            beforeSend: function () {
                // عرض رسالة "جاري الإرسال" أو تعطيل الزر أثناء الإرسال
                $('#login-form button[type="submit"]').prop('disabled', true).text('Logging in...');
            },
            success: function (response) {
                $('#login-form button[type="submit"]').prop('disabled', false).text('Login');

                if (response.success) {
                    // تحقق من وجود redirect_url
                    if (response.data.redirect_url) {
                        $('#login-success').removeClass('hidden').text('Login successful! Redirecting...');
                        setTimeout(function () {
                            window.location.href = response.data.redirect_url;
                        }, 1500); // انتظار قليل قبل إعادة التوجيه
                    } else {
                        $('#login-error').removeClass('hidden').text('Redirect URL is missing.');
                    }
                } else {
                    $('#login-error').removeClass('hidden').text(response.data.message || 'Invalid login credentials.');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#login-form button[type="submit"]').prop('disabled', false).text('Login');
                console.error('AJAX error:', textStatus, errorThrown);
                $('#login-error').removeClass('hidden').text('An unexpected error occurred. Please try again.');
            }
        });
    });
});
