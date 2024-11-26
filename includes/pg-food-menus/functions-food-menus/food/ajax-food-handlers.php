<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function handle_add_food_item() {
    // التحقق من الـ nonce للأمان
    check_ajax_referer('food_ajax_nonce', 'nonce');

    // استلام البيانات
    $food_title = sanitize_text_field($_POST['food_title']);
    $food_description = sanitize_textarea_field($_POST['food_description']);
    $food_price = floatval($_POST['food_price']);
    $parent_category = intval($_POST['parent_category']);
    $food_image = intval($_POST['food_image']);

    // التحقق من المدخلات
    if (empty($food_title) || empty($food_description) || $food_price <= 0) {
        wp_send_json_error(['message' => 'يرجى ملء جميع الحقول المطلوبة.']);
    }

    // إنشاء المنشور الجديد
    $new_post = wp_insert_post([
        'post_title'   => $food_title,
        'post_content' => $food_description,
        'post_type'    => 'pg_food_menu',
        'post_status'  => 'publish',
    ]);

    if (is_wp_error($new_post)) {
        wp_send_json_error(['message' => 'فشل في إضافة العنصر.']);
    }

    // إضافة الفئة (إن وجدت)
    if ($parent_category > 0) {
        wp_set_object_terms($new_post, $parent_category, 'pg_food_menu_category');
    }

    // إضافة الصورة المميزة (إن وجدت)
    if ($food_image > 0) {
        set_post_thumbnail($new_post, $food_image);
    }

    // إضافة السعر كـ Meta Data
    update_post_meta($new_post, '_food_price', $food_price);

    wp_send_json_success(['message' => 'تمت إضافة العنصر بنجاح.', 'post_id' => $new_post]);
}
add_action('wp_ajax_add_food_item', 'handle_add_food_item');
add_action('wp_ajax_nopriv_add_food_item', 'handle_add_food_item'); // للمستخدمين غير المسجلين