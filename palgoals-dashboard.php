<?php
/*
Plugin Name: Palgoals Dashboard
Plugin URI: https://palgoals.com
Description: Palgoals Dashboard plugin with multi-language support
Version: v1.0.0
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
require_once plugin_dir_path(__FILE__) . 'includes/pg-food-menus/food-menus.php';
require_once plugin_dir_path(__FILE__) . 'includes/media.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/pg-Settings.php';


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


function palgoals_dashboard_plugin_update_check() {
    // عنوان URL لخادم التحديث (في حال كنت تستخدم GitHub)
    $update_url = 'https://api.github.com/repos/palgooal/palgoals-dashboard/releases/latest';

    // إرسال طلب GET للتحقق من آخر إصدار
    $response = wp_remote_get($update_url);
    
    if (is_wp_error($response)) {
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (empty($data)) {
        return;
    }

    // تحديد إصدار الإضافة الحالية
    $current_version = get_plugin_data( __FILE__ )['Version'];

    // تحديد الإصدار الجديد
    $latest_version = $data['tag_name'] ?? null;

    if (version_compare($current_version, $latest_version, '<')) {
        // هناك تحديث جديد متاح
        $update_message = "هناك تحديث جديد للإضافة! إصدار جديد: $latest_version";
        // يمكنك هنا إرسال إشعار إلى المستخدم أو تحديث واجهة المستخدم
        add_action('admin_notices', function() use ($update_message) {
            echo '<div class="notice notice-warning is-dismissible"><p>' . $update_message . '</p></div>';
        });
    }
}
add_action('admin_init', 'palgoals_dashboard_plugin_update_check');
