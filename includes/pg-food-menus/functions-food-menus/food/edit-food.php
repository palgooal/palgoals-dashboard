<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

/**
 * إعادة كتابة القواعد لرابط صفحة تحرير الطعام
 */
function palgoals_edit_food_dashboard_rewrite_rule() {
    add_rewrite_rule(
        '^dashboard/pg-menus/edit-food?$',
        'index.php?edit_food=true',
        'top'
    );
}
add_action('init', 'palgoals_edit_food_dashboard_rewrite_rule');

/**
 * إضافة متغير query جديد لـ edit_food
 */
function palgoals_edit_food_query_vars($vars) {
    $vars[] = 'edit_food';
    return $vars;
}
add_filter('query_vars', 'palgoals_edit_food_query_vars');

/**
 * عرض صفحة تحرير الطعام إذا كان المتغير edit_food موجودًا
 */
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
 * تحميل ملفات JavaScript وCSS المخصصة لصفحة تحرير الطعام
 */
function palgoals_enqueue_edit_food_dashboard_assets() {
    if (get_query_var('edit_food')) {
        // تحميل الموارد المشتركة
        palgoals_enqueue_shared_assets();
        wp_enqueue_media(); // لتحميل مكتبة الوسائط

        // تحميل ملفات JavaScript
        wp_enqueue_script(
            'palgoals-ajax-script',
            plugin_dir_url(__DIR__) . 'js/upload-image-edit.js',
            ['jquery'],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_edit_food_dashboard_assets');

/**
 * تعطيل ملفات CSS الخاصة بالثيم في صفحة تحرير الطعام
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
 * معالجة تحديث بيانات الطعام
 */
function palgoals_update_food_handler() {
    // تحقق من nonce للحماية
    if (!isset($_POST['update_food_nonce']) || !wp_verify_nonce($_POST['update_food_nonce'], 'update_food')) {
        wp_die(__('Security check failed', 'palgoals-core'));
    }

    // جلب معرف الطعام
    $food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;

    if ($food_id) {
        // تحديث بيانات المنشور
        wp_update_post([
            'ID'           => $food_id,
            'post_title'   => sanitize_text_field($_POST['food_title']),
            'post_content' => sanitize_textarea_field($_POST['food_description']),
        ]);

        // تحديث البيانات الوصفية
        update_post_meta($food_id, '_pg_food_menu_price', sanitize_text_field($_POST['food_price']));
        update_post_meta($food_id, '_food_image', intval($_POST['image_id']));

        // تحديث التصنيفات
        if (isset($_POST['food_category'])) {
            wp_set_post_terms($food_id, $_POST['food_category'], 'pg_food_menu_category');
        }

        // إعادة التوجيه مع رسالة نجاح
        wp_safe_redirect(
            add_query_arg(['id' => $food_id, 'updated' => 'true'], home_url('/dashboard/pg-menus/edit-food/'))
        );
        exit;
    } else {
        wp_die(__('Invalid food ID', 'palgoals-core'));
    }
}
add_action('admin_post_update_food', 'palgoals_update_food_handler');

/**
 * إضافة صلاحيات خاصة لنوع المنشور المخصص
 */
function palgoals_add_food_menu_caps() {
    // جلب الدور "Administrator"
    $role = get_role('administrator');

    if ($role) {
        // إضافة الصلاحيات لنوع المنشور
        $role->add_cap('edit_pg_food_menu');
        $role->add_cap('read_pg_food_menu');
        $role->add_cap('delete_pg_food_menu');
        $role->add_cap('edit_pg_food_menus');
        $role->add_cap('edit_others_pg_food_menus');
        $role->add_cap('publish_pg_food_menus');
        $role->add_cap('read_private_pg_food_menus');
    }
}
add_action('admin_init', 'palgoals_add_food_menu_caps');
