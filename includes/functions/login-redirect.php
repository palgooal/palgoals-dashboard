<?php
// إعادة توجيه تسجيل الدخول الافتراضي إلى صفحة مخصصة
function redirect_to_login() {
    // الرابط المخصص لصفحة تسجيل الدخول
    $login_page = home_url('/login/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);
    // تحقق إذا كان المستخدم لم يقم بتسجيل الدخول وهو يحاول الوصول إلى wp-login.php أو wp-admin
    if (($page_viewed == "wp-login.php" || $page_viewed == "wp-admin") && !is_user_logged_in()) {
        // استثناء: السماح بعمليات POST لتسجيل الدخول دون إعادة التوجيه
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return;
        }
        // إعادة التوجيه إلى صفحة تسجيل الدخول المخصصة
        wp_redirect($login_page);
        exit;
    }
}
add_action('init', 'redirect_to_login');

function restrict_wp_admin_access() {
    // تحقق من أن المستخدم مسجل الدخول ويقوم بمحاولة الوصول إلى wp-admin
    if (is_admin() && !defined('DOING_AJAX')) {
        $current_user = wp_get_current_user();

        if ($current_user->user_login === 'support') {
            return;
        }


        // السماح للمستخدمين بالوصول إلى رابط تحرير Elementor
        if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
            return;
        }
        // إعادة توجيه المستخدمين الآخرين إلى /dashboard/ أو الصفحة الرئيسية
        wp_redirect(site_url('/dashboard/'));
        exit;
    }
}
add_action('admin_init', 'restrict_wp_admin_access');