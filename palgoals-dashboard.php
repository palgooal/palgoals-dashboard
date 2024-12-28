<?php
/*
Plugin Name: Palgoals Dashboard
Plugin URI: https://palgoals.com
Description: Palgoals Dashboard plugin with multi-language support
Version: v1.2.0
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


function palgoals_dashboard_plugin_update_check($transient) {
    // إعداد البيانات
    $plugin_slug = 'palgoals-dashboard/palgoals-dashboard.php'; // مسار الإضافة الرئيسي
    $update_url = 'https://api.github.com/repos/palgooal/palgoals-dashboard/releases/latest';
    
    // إرسال طلب GET للتحقق من آخر إصدار
    $response = wp_remote_get($update_url, [
        'timeout' => 15, // تحديد وقت المهلة
        'headers' => [
            'User-Agent' => 'Palgoals-Plugin/1.0',
        ]
    ]);

    if (is_wp_error($response)) {
        return $transient;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data) || empty($data['tag_name'])) {
        return $transient;
    }

    // تحديد الإصدار الجديد
    $current_version = get_plugin_data(__FILE__)['Version'];
    $latest_version = $data['tag_name'];

    if (version_compare($current_version, $latest_version, '<')) {
        // إعداد تفاصيل التحديث
        $transient->response[$plugin_slug] = (object) [
            'slug'        => $plugin_slug,
            'plugin'      => $plugin_slug,
            'new_version' => $latest_version,
            'package'     => $data['assets'][0]['browser_download_url'] ?? null, // رابط الملف القابل للتنزيل
            'tested'      => '6.7.1', // إصدار ووردبريس الذي تم اختباره معه
            'requires'    => '6.0', // إصدار ووردبريس المطلوب
        ];
    }

    return $transient;
}
add_filter('site_transient_update_plugins', 'palgoals_dashboard_plugin_update_check');

// تمكين التحديث التلقائي للإضافة
function palgoals_enable_auto_update($update, $item) {
    // تحقق من اسم الإضافة
    if (isset($item->slug) && $item->slug === 'palgoals-dashboard') {
        return true; // تمكين التحديث التلقائي
    }

    return $update; // القيمة الافتراضية
}
add_filter('auto_update_plugin', 'palgoals_enable_auto_update', 10, 2);

// إضافة معلومات إضافية في صفحة الإضافات
function palgoals_dashboard_plugin_update_info($plugin_data, $response) {
    if (!empty($response) && !empty($response->new_version)) {
        echo '<p>آخر إصدار متاح: ' . esc_html($response->new_version) . '</p>';
    }
}
add_action('after_plugin_row_palgoals-dashboard/palgoals-dashboard.php', 'palgoals_dashboard_plugin_update_info', 10, 2);

