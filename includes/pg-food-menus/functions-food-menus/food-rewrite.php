<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function palgoals_menus_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-menus/?$', 'index.php?pg_menus=true', 'top');
}
add_action('init', 'palgoals_menus_dashboard_rewrite_rule');

function palgoals_menus_query_vars($vars) {
    $vars[] = 'pg_menus';
    return $vars;
}
add_filter('query_vars', 'palgoals_menus_query_vars');

function palgoals_menus_dashboard_page() {
    if (get_query_var('pg_menus')) {
        if (is_user_logged_in()) {
            include plugin_dir_path(__DIR__) . '/templates/food/food-menu.php';      
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_menus_dashboard_page');

