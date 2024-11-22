<?php
add_action('wp_ajax_palgoals_add_menu_category', 'palgoals_add_menu_category');
add_action('wp_ajax_nopriv_palgoals_add_menu_category', 'palgoals_add_menu_category'); // للسماح لغير المسجلين إذا لزم الأمر

function palgoals_add_menu_category() {
    // التحقق من nonce للحماية
    check_ajax_referer('add_category_nonce', 'nonce');

    // جلب البيانات من الطلب
    $name = sanitize_text_field($_POST['name']);
    $slug = sanitize_title($_POST['slug']);
    $parent = intval($_POST['parent']);
    $description = sanitize_textarea_field($_POST['description']);
    $image_id = intval($_POST['image_id']);

    // إدخال التصنيف
    $term = wp_insert_term($name, 'pg_food_menu_category', [
        'slug' => $slug,
        'parent' => $parent,
        'description' => $description,
    ]);

    if (is_wp_error($term)) {
        wp_send_json_error(['message' => $term->get_error_message()]);
    } else {
        if ($image_id) {
            update_term_meta($term['term_id'], 'category_image', $image_id);
        }
        wp_send_json_success(['message' => 'Category added successfully', 'term_id' => $term['term_id']]);
    }
}

?>