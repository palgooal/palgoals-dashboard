<?php
/*
Plugin Name: Palgoals Dashboard
Plugin URI: https://palgoals.com
Description: Palgoals Dashboard plugin with multi-language support
Version: 1.0
Author: hazem alyahya
Author URI: https://palgoals.com
License: GPLv2 or later
Text Domain: palgoals-dash
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// تحميل ملفات الترجمة للإضافة
function palgoals_load_textdomain() {
    load_plugin_textdomain('palgoals-dash', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'palgoals_load_textdomain');
require_once plugin_dir_path(__FILE__) . 'includes/admin/login-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/pages-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/pg-food-menus/menus-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/pg-food-menus/category-menus-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/media.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';


add_filter('show_admin_bar', '__return_false');


// عند تفعيل الإضافة
function palgoals_activate_plugin() {
    palgoals_dashboard_rewrite_rule();
    palgoals_pages_dashboard_rewrite_rule();
    flush_rewrite_rules(); // تحديث قواعد إعادة الكتابة
}
register_activation_hook(__FILE__, 'palgoals_activate_plugin');

// عند إلغاء تفعيل الإضافة
function palgoals_deactivate_plugin() {
    flush_rewrite_rules(); // إعادة القواعد لحالتها الافتراضية
}
register_deactivation_hook(__FILE__, 'palgoals_deactivate_plugin');

// تحديث الروابط الدائمة عند إعادة تفعيل الإضافة
add_action('admin_init', function() {
    if (is_plugin_active('palgoals-dashboard/palgoals-dashboard.php')) {
        flush_rewrite_rules();
    }
});

