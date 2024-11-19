<?php
// إضافة قاعدة إعادة الكتابة لإنشاء رابط مخصص لـ dashboard
function palgoals_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/?$', 'index.php?dashboard=true', 'top');
}
add_action('init', 'palgoals_dashboard_rewrite_rule');

// إضافة المتغير المخصص إلى query_vars
function palgoals_add_query_vars($vars) {
    $vars[] = 'dashboard';
    return $vars;
}
add_filter('query_vars', 'palgoals_add_query_vars');

// فحص المتغير وتوجيه الصفحة
function palgoals_custom_dashboard_page() {
    if (get_query_var('dashboard') == 'true') {
        // التحقق من تسجيل دخول المستخدم
        if(is_user_logged_in()){
            // تحميل صفحة لوحة التحكم المخصصة
            include plugin_dir_path(dirname(__DIR__, 2)) . 'templates/dashboard.php';
        } else {
            // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
            wp_redirect(wp_login_url());
            exit;
        }
        exit; // التأكد من إيقاف باقي تنفيذ الصفحات
    }
}
add_action('template_redirect', 'palgoals_custom_dashboard_page');