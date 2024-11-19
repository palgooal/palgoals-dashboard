<?php
if (!defined('ABSPATH')) exit; // منع الوصول المباشر

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم
function palgoals_enqueue_dashboard_assets() {
    if (get_query_var('dashboard') === 'true') {
        palgoals_enqueue_shared_assets();
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_dashboard_remove_theme_styles() {
    if (get_query_var('dashboard') === 'true') { // تحقق إذا كانت الصفحة هي لوحة التحكم المخصصة
        global $wp_styles;
        
        // تعطيل جميع ملفات CSS التي يضيفها الثيم
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_dashboard_remove_theme_styles', 99);