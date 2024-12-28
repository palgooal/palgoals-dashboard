<?php
if (!defined('ABSPATH')) exit; // منع الوصول المباشر

// إضافة قاعدة إعادة الكتابة لإنشاء رابط مخصص لـ dashboard/pg-pages
function palgoals_pg_settings_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-settings/?$', 'index.php?pg_settings=true', 'top');
}
add_action('init', 'palgoals_pg_settings_dashboard_rewrite_rule');

// إضافة المتغير المخصص إلى query_vars
function palgoals_pg_settings_query_vars($vars) {
    $vars[] = 'pg_settings';
    return $vars;
}
add_filter('query_vars', 'palgoals_pg_settings_query_vars');

// توجيه الصفحة إلى قالب مخصص عند التحقق من المتغير
function palgoals_pg_settings_dashboard_page() {
    if (get_query_var('pg_settings') === 'true') {
        // التحقق من تسجيل دخول المستخدم
        if (is_user_logged_in()) {
            include plugin_dir_path(dirname(__DIR__, 1)) . 'templates/pg-settings.php';
            } else {
                // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
                wp_redirect(wp_login_url());
                exit;
            }
            exit;
        }
    }
add_action('template_redirect', 'palgoals_pg_settings_dashboard_page');

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم
function palgoals_enqueue_pg_settings_assets() {
    if (get_query_var('pg_settings') === 'true') {
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
              // تحميل ملفات JavaScript
              wp_enqueue_script(
                'palgoals-ajax-script',
                plugin_dir_url(dirname(__DIR__)) . 'assets/js/pg-pages/pg-settings.js',
                array('jquery'),
                null,
                true
            );

         // تمرير بيانات الـ AJAX والـ nonce إلى JavaScript
        wp_localize_script(
            'palgoals-ajax-script',
            'ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('settings_ajax_nonce'),
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_pg_settings_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_pg_settings_remove_theme_styles() {
    if (get_query_var('pg_settings') === 'true') { // تحقق إذا كانت الصفحة هي لوحة التحكم المخصصة
        global $wp_styles;
        
        // تعطيل جميع ملفات CSS التي يضيفها الثيم
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_pg_settings_remove_theme_styles', 99);

// تعطيل ملفات الـ JavaScript الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_pg_settings_remove_theme_scripts() {
    if (get_query_var('pg_settings') === 'true') { // تحقق إذا كانت الصفحة هي لوحة التحكم المخصصة
        global $wp_scripts;
        
        // تعطيل جميع ملفات الـ JavaScript التي يضيفها الثيم
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($wp_scripts->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_script($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_pg_settings_remove_theme_scripts', 99);

// معالجة حفظ التعديلات من خلال AJAX
// معالجة حفظ التعديلات من خلال AJAX
function palgoals_save_pg_settings() {
    // التحقق من nonce لضمان الأمان
    check_ajax_referer('settings_ajax_nonce', 'security');

    // التحقق من صلاحيات المستخدم
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized access.']);
    }

    // جمع البيانات المرسلة
    $site_title = sanitize_text_field($_POST['site_title']);
    $tagline = sanitize_text_field($_POST['tagline']);
    $site_language = sanitize_text_field($_POST['site_language']);
    $site_icon_url = sanitize_text_field($_POST['site_icon_url']);

    // تحديث الخيارات في قاعدة البيانات
    if (!empty($site_title)) {
        update_option('blogname', $site_title);
    }
    if (!empty($tagline)) {
        update_option('blogdescription', $tagline);
    }
    if (!empty($site_language)) {
        // تحميل حزمة اللغة إذا لم تكن مثبتة
        if (!in_array($site_language, get_available_languages())) {
            $language_pack = wp_download_language_pack($site_language);
            if (is_wp_error($language_pack)) {
                wp_send_json_error(['message' => 'Failed to download language pack.']);
            }
        }
        update_option('WPLANG', $site_language);
        switch_to_locale($site_language);
    }
    if (!empty($site_icon_url)) {
        update_option('site_icon', attachment_url_to_postid($site_icon_url));
    }

    // إرسال رد JSON للعميل
    wp_send_json_success(['message' => 'Settings updated successfully.']);
}
add_action('wp_ajax_save_pg_settings', 'palgoals_save_pg_settings');

// معالجة رفع الشعار وحفظه
function update_site_identity_logo() {
    // التحقق من الأمان والصلاحيات
    check_ajax_referer('site_logo_nonce', 'security');

    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(['message' => 'Unauthorized access.']);
    }

    $attachment_id = absint($_POST['attachment_id']);
    if ($attachment_id) {
        set_theme_mod('custom_logo', $attachment_id);
        wp_send_json_success(['message' => 'Logo updated successfully.']);
    } else {
        wp_send_json_error(['message' => 'Invalid attachment ID.']);
    }
}
add_action('wp_ajax_update_site_identity_logo', 'update_site_identity_logo');

