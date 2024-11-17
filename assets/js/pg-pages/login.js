jQuery(document).ready(function($) {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_login_action', // يجب أن يتطابق مع اسم الدالة في PHP
                email: email,
                password: password,
            },
            success: function(response) {
                if (response.success) {
                    // تحقق مما إذا كانت redirect_url متاحة
                    if (response.data.redirect_url) {
                        $('#login-success').removeClass('hidden').text('Login successful! Redirecting...');
                        window.location.href = response.data.redirect_url;
                    } else {
                        console.error('Redirect URL is undefined.');
                    }
                } else {
                    $('#login-error').removeClass('hidden').text(response.data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                $('#login-error').removeClass('hidden').text('An error occurred during the login process.');
            }
        });
    });
});