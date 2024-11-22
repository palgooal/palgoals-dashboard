<?php
// تسجيل قاعدة إعادة الكتابة للصفحة المخصصة
function palgoals_menus_category_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-category-menus/?$', 'index.php?pg_category_menus=true', 'top');
}
add_action('init', 'palgoals_menus_category_dashboard_rewrite_rule');

// إضافة متغير جديد للصفحة المخصصة
function palgoals_menus_category_query_vars($vars) {
    $vars[] = 'pg_category_menus';
    return $vars;
}
add_filter('query_vars', 'palgoals_menus_category_query_vars');

// عرض صفحة التصنيفات المخصصة
function palgoals_menus_category_dashboard_page() {
    if (get_query_var('pg_category_menus')) {
        if (is_user_logged_in()) {
            include dirname(__DIR__, 4) . '/templates/menus/category-menus.php';
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_menus_category_dashboard_page');