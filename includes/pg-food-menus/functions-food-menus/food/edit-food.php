<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function palgoals_edit_food_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-menus/edit-food?$', 'index.php?edit_food=true', 'top');
}
add_action('init', 'palgoals_edit_food_dashboard_rewrite_rule');

function palgoals_edit_food_query_vars($vars) {
    $vars[] = 'edit_food';
    return $vars;
}
add_filter('query_vars', 'palgoals_edit_food_query_vars');

function palgoals_edit_food_dashboard_page() {
    if (get_query_var('edit_food')) {
        if (is_user_logged_in()) {
            include plugin_dir_path(dirname(__DIR__)) . '/templates/food/edit-food.php';
         } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_edit_food_dashboard_page');


/**
 * تحميل ملفات الجافاسكريبت والموارد الخاصة بلوحة التحكم المخصصة
 */
function palgoals_enqueue_edit_food_dashboard_assets() {
    if (get_query_var('edit_food')) {
        // تحميل الموارد المشتركة
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
        
        // تحميل ملفات JavaScript
        wp_enqueue_script(
            'palgoals-ajax-script',
            plugin_dir_url(__DIR__) . 'js/upload-image-edit.js',
            array('jquery'),
            null,
            true
        );


    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_edit_food_dashboard_assets');

/**
 * تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
 */
function palgoals_remove_theme_edit_food() {
    if (get_query_var('edit_food')) {
        global $wp_styles;
        
        // إزالة جميع ملفات CSS الخاصة بالثيم
        foreach ($wp_styles->queue as $handle) {
            if (
                isset($wp_styles->registered[$handle]) &&
                strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0
            ) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_edit_food', 99);

/**
 * تحميل سكربتات خاصة لإضافة صور التصنيفات في صفحات التصنيفات
 */
// function enqueue_category_edit_food_image_script($hook_suffix) {
//     if ('term.php' === $hook_suffix || 'edit-tags.php' === $hook_suffix) {
//         wp_enqueue_media();
//         wp_enqueue_script(
//             'category-image-script',
//             plugin_dir_url(dirname(__DIR__, 2)) . 'assets/js/pg-pages/category-image.js',
//             array('jquery'),
//             null,
//             true
//         );
//     }
// }
// add_action('admin_enqueue_scripts', 'enqueue_category_edit_foodimage_script');
