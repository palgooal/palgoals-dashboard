<?php
// إنشاء قاعدة إعادة كتابة لصفحة تسجيل الدخول
function palgoals_login_dashboard_rewrite_rule() {
    add_rewrite_rule('^login/?$', 'index.php?login=true', 'top');
}
add_action('init', 'palgoals_login_dashboard_rewrite_rule');

// إضافة متغير استعلام جديد
function palgoals_login_query_vars($vars) {
    $vars[] = 'login';
    return $vars;
}
add_filter('query_vars', 'palgoals_login_query_vars');

// توجيه إلى قالب مخصص عند زيارة صفحة تسجيل الدخول
function palgoals_login_page() {
    if (get_query_var('login') === 'true') {
        include dirname(__DIR__, 2) . '/templates/login.php';
        exit;
    }
}
add_action('template_redirect', 'palgoals_login_page');
