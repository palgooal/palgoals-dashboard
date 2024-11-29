<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

/**
 * إعادة كتابة القواعد لرابط صفحة تحرير الطعام
 */
function palgoals_edit_category_dashboard_rewrite_rule() {
    add_rewrite_rule(
        '^dashboard/pg-category-menus/edit-category?$',
        'index.php?edit_category=true',
        'top'
    );
}
add_action('init', 'palgoals_edit_category_dashboard_rewrite_rule');

/**
 * إضافة متغير query جديد لـ edit_food
 */
function palgoals_edit_category_query_vars($vars) {
    $vars[] = 'edit_category';
    return $vars;
}
add_filter('query_vars', 'palgoals_edit_category_query_vars');

/**
 * عرض صفحة تحرير الطعام إذا كان المتغير edit_food موجودًا
 */
function palgoals_edit_category_dashboard_page() {
    if (get_query_var('edit_category')) {
        if (is_user_logged_in()) {
            include plugin_dir_path(dirname(__DIR__)) . '/templates/taxonomies/edit-categories.php';
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_edit_category_dashboard_page');

/**
 * تحميل ملفات JavaScript وCSS المخصصة لصفحة تحرير الطعام
 */
function palgoals_enqueue_edit_category_dashboard_assets() {
    if (get_query_var('edit_category')) {
        // تحميل الموارد المشتركة
        palgoals_enqueue_shared_assets();
        wp_enqueue_media(); // لتحميل مكتبة الوسائط

        // تحميل ملفات JavaScript
        // wp_enqueue_script(
        //     'palgoals-ajax-script',
        //     plugin_dir_url(__DIR__) . 'js/upload-image-edit.js',
        //     ['jquery'],
        //     null,
        //     true
        // );

        wp_enqueue_script(
            'palgoals-upload-image-categories',
            plugin_dir_url(__DIR__) . 'js/upload-image-categories.js',
            ['jquery'],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_edit_category_dashboard_assets');

/**
 * تعطيل ملفات CSS الخاصة بالثيم في صفحة تحرير الطعام
 */
function palgoals_remove_theme_edit_category() {
    if (get_query_var('edit_category')) {
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
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_edit_category', 99);


