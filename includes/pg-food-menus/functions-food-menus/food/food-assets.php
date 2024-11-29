<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

/**
 * تحميل ملفات الجافاسكريبت والموارد الخاصة بلوحة التحكم المخصصة
 */
function palgoals_enqueue_menus_dashboard_assets() {
    if (get_query_var('pg_menus')) {
        // تحميل الموارد المشتركة
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
        
        // تحميل ملفات JavaScript
        wp_enqueue_script(
            'palgoals-ajax-script',
            plugin_dir_url(__DIR__) . 'js/add-food-ajax.js',
            array('jquery'),
            null,
            true
        );

        wp_enqueue_script(
            'palgoals-upload-image',
            plugin_dir_url(__DIR__) . 'js/upload-image.js',
            array('jquery'),
            null,
            true
        );

        wp_enqueue_script(
            'palgoals-delete-food-script',
            plugin_dir_url(__DIR__) . 'js/delete-food-item.js',
            ['jquery'],
            null,
            true
        );

        // تمرير بيانات Ajax إلى السكربت
        wp_localize_script('palgoals-ajax-script', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('food_ajax_nonce'),
        ));

        wp_localize_script('palgoals-delete-food-script', 'palgoalsData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('food_ajax_nonce'),
        ]);


    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_menus_dashboard_assets');

/**
 * تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
 */
function palgoals_remove_theme_menus() {
    if (get_query_var('pg_menus')) {
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
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_menus', 99);

/**
 * تحميل سكربتات خاصة لإضافة صور التصنيفات في صفحات التصنيفات
 */
function enqueue_category_image_script($hook_suffix) {
    if ('term.php' === $hook_suffix || 'edit-tags.php' === $hook_suffix) {
        wp_enqueue_media();
        wp_enqueue_script(
            'category-image-script',
            plugin_dir_url(dirname(__DIR__, 2)) . 'assets/js/pg-pages/category-image.js',
            array('jquery'),
            null,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'enqueue_category_image_script');
