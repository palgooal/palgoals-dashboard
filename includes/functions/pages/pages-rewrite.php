<?php
if (!defined('ABSPATH')) exit; // منع الوصول المباشر

// إضافة قاعدة إعادة الكتابة لإنشاء رابط مخصص لـ dashboard/pg-pages
function palgoals_pages_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-pages/?$', 'index.php?pg_pages=true', 'top');
}
add_action('init', 'palgoals_pages_dashboard_rewrite_rule');

// إضافة المتغير المخصص إلى query_vars
function palgoals_add_pages_query_vars($vars) {
    $vars[] = 'pg_pages';
    return $vars;
}
add_filter('query_vars', 'palgoals_add_pages_query_vars');

// توجيه الصفحة إلى قالب مخصص عند التحقق من المتغير
function palgoals_pages_dashboard_page() {
    if (get_query_var('pg_pages') === 'true') {
        // التحقق من تسجيل دخول المستخدم
        if (is_user_logged_in()) {
            include plugin_dir_path(dirname(__DIR__, 2)) . 'templates/pg-pages.php';
            } else {
                // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
                wp_redirect(wp_login_url());
                exit;
            }
            exit;
        }
    }
add_action('template_redirect', 'palgoals_pages_dashboard_page');