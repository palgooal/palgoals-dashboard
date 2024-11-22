<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

// حذف التصنيف عبر Ajax
add_action('wp_ajax_delete_food_menu_category', 'delete_food_menu_category');
function delete_food_menu_category() {
    // تحقق من الـ Nonce
    check_ajax_referer('delete_category_nonce', 'nonce');

    // التحقق من صلاحيات المستخدم
    if (!current_user_can('manage_categories')) {
        wp_send_json_error(['error' => __('You do not have permission to delete categories.', 'palgoals-dash')]);
    }

    // تحقق من وجود معرف التصنيف
    if (!isset($_POST['category_id']) || empty($_POST['category_id'])) {
        wp_send_json_error(['error' => __('Invalid category ID.', 'palgoals-dash')]);
    }

    $category_id = intval($_POST['category_id']);

    // حاول حذف التصنيف
    $deleted = wp_delete_term($category_id, 'pg_food_menu_category');

    if (is_wp_error($deleted)) {
        wp_send_json_error(['error' => $deleted->get_error_message()]);
    }

    // نجاح الحذف
    wp_send_json_success(['message' => __('Category deleted successfully.', 'palgoals-dash')]);
}