<?php
// معالجة تسجيل الدخول باستخدام AJAX
function custom_login_action() {
    // تحقق من وجود بيانات POST
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        wp_send_json_error(['message' => 'Email and password are required']);
        return;
    }

    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    $user = wp_authenticate($email, $password);

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Invalid email or password']);
    } else {
        // تسجيل دخول المستخدم
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        // تحدي�� رابط إعادة التوجيه بعد تسجيل الدخول
        $redirect_url = $user->user_login === 'support' ? admin_url() : site_url('/dashboard/');
        wp_send_json_success(['redirect_url' => $redirect_url]);
    }
}

add_action('wp_ajax_custom_login_action', 'custom_login_action');
add_action('wp_ajax_nopriv_custom_login_action', 'custom_login_action');
