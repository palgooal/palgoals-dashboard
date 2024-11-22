<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function palgoals_enqueue_category_dashboard_assets() {
    if (get_query_var('pg_category_menus')) {
        // تضمين ملفات مشتركة
        palgoals_enqueue_shared_assets();

        // تحميل مكتبة الصور
        wp_enqueue_media();

        // سكربت إدارة الصور
        wp_enqueue_script(
            'palgoals-category-image',
            plugin_dir_url(dirname(__DIR__, 3)) . 'assets/js/pg-pages/category-image.js',
            array('jquery'),
            null,
            true
        );

        // سكربت حذف التصنيف
        wp_enqueue_script(
            'delete-food-category',
            plugin_dir_url(__DIR__) . 'taxonomies/js/delete-food-category.js',
            array('jquery'),
            null,
            true
        );

        // تمرير متغيرات JavaScript للـ Ajax
        wp_localize_script('delete-food-category', 'deleteCategoryAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('delete_category_nonce'),
            'confirmMessage' => __('Are you sure you want to delete this category?', 'palgoals-dash'),
            'errorMessage' => __('An error occurred. Please try again.', 'palgoals-dash')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_category_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم
function palgoals_remove_theme_category() {
    if (get_query_var('pg_category_menus')) {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (isset($wp_styles->registered[$handle]) && strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_category', 99);
