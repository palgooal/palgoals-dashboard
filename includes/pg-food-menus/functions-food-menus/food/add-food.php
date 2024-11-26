<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function palgoals_add_food_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-menus/add-food?$', 'index.php?add_food=true', 'top');
}
add_action('init', 'palgoals_add_food_dashboard_rewrite_rule');

function palgoals_add_food_query_vars($vars) {
    $vars[] = 'add_food';
    return $vars;
}
add_filter('query_vars', 'palgoals_add_food_query_vars');

function palgoals_add_food_dashboard_page() {
    if (get_query_var('add_food')) {
        if (is_user_logged_in()) {
            include plugin_dir_path(dirname(__DIR__)) . 'templates/food/add-food.php'; 
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_add_food_dashboard_page');

//تحميل الستايل

function palgoals_enqueue_add_food_dashboard_assets() {
    if (get_query_var('add_food')) {
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_add_food_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_remove_theme_add_food() {
    if (get_query_var('add_food')) {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (isset($wp_styles->registered[$handle]) && strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_add_food', 99);