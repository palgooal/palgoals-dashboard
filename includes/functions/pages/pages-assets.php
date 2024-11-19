<?php
if (!defined('ABSPATH')) exit; // منع الوصول المباشر

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم المخصصة
function palgoals_enqueue_pages_dashboard_assets() {
    if (get_query_var('pg_pages') === 'true') {
        wp_enqueue_script('palgoals-sadd-pages', plugin_dir_url(dirname(__DIR__, 2)) . 'assets/js/pg-pages/add-pages.js', array('jquery'), null, true);
        palgoals_enqueue_shared_assets();
        wp_enqueue_script('palgoals-delete-pages', plugin_dir_url(dirname(__DIR__, 2)) . 'assets/js/pg-pages/delete-pages.js', array('jquery'), null, true);
        //wp_enqueue_script('palgoals-status-pages', plugin_dir_url(__DIR__) . 'assets/js/pg-pages/status.js', array('jquery'), null, true);
        
        // Localize the script to pass data
        wp_localize_script('palgoals-delete-pages', 'palgoals_delete_pages_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('palgoals_delete_page_nonce')
        ));
        wp_localize_script('palgoals-sadd-pages', 'palgoals_sadd_pages_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('palgoals_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_pages_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_remove_theme_styles() {
    if (get_query_var('pg_pages') === 'true') {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_styles', 99);