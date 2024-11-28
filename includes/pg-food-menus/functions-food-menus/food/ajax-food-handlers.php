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
    update_post_meta($new_post, '_pg_food_menu_price', $food_price);

    wp_send_json_success(['message' => 'تمت إضافة العنصر بنجاح.', 'post_id' => $new_post]);
}
add_action('wp_ajax_add_food_item', 'handle_add_food_item');
add_action('wp_ajax_nopriv_add_food_item', 'handle_add_food_item'); // للمستخدمين غير المسجلين



function update_food_item() {
    check_ajax_referer('food_ajax_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $food_title = sanitize_text_field($_POST['food_title']);
    $food_description = sanitize_textarea_field($_POST['food_description']);
    $food_price = floatval($_POST['food_price']);
    $parent_category = intval($_POST['parent_category']);
    $food_image = intval($_POST['food_image']);

    if (!$post_id || empty($food_title) || empty($food_description) || $food_price <= 0) {
        wp_send_json_error(['message' => 'يرجى ملء جميع الحقول المطلوبة.']);
    }

    // تعديل المنشور
    $updated_post = wp_update_post([
        'ID'           => $post_id,
        'post_title'   => $food_title,
        'post_content' => $food_description,
    ], true);

    if (is_wp_error($updated_post)) {
        wp_send_json_error(['message' => 'فشل في حفظ التعديلات.']);
    }

    // تعديل التصنيفات
    if ($parent_category > 0) {
        wp_set_object_terms($post_id, $parent_category, 'pg_food_menu_category');
    }

    // تعديل الصورة المميزة
    if ($food_image > 0) {
        set_post_thumbnail($post_id, $food_image);
    }

    // تعديل السعر
    update_post_meta($post_id, '_food_price', $food_price);

    wp_send_json_success(['message' => 'تم حفظ التعديلات بنجاح.']);
}
add_action('wp_ajax_update_food_item', 'update_food_item');


// function get_food_item() {
//     check_ajax_referer('food_ajax_nonce', 'nonce');

//     $post_id = intval($_POST['post_id']);

//     if (!$post_id) {
//         wp_send_json_error(['message' => 'معرف المنشور غير موجود.']);
//     }

//     $post = get_post($post_id);

//     if (!$post || $post->post_type !== 'pg_food_menu') {
//         wp_send_json_error(['message' => 'المنشور غير موجود أو نوعه غير صحيح.']);
//     }

//     $category = wp_get_post_terms($post_id, 'pg_food_menu_category', ['fields' => 'ids']);
//     $image_id = get_post_thumbnail_id($post_id);
//     $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

//     wp_send_json_success([
//         'post_id'    => $post_id,
//         'title'      => $post->post_title,
//         'description' => $post->post_content,
//         'price'      => get_post_meta($post_id, '_food_price', true),
//         'category'   => $category ? $category[0] : 0,
//         'image_id'   => $image_id,
//         'image_url'  => $image_url,
//     ]);
// }
// add_action('wp_ajax_get_food_item', 'get_food_item');





// جلب بيانات العنصر
add_action('wp_ajax_get_food_menu_item', 'get_food_menu_item');
function get_food_menu_item() {
    $post_id = intval($_POST['post_id']);
    if (!$post_id) {
        wp_send_json_error(['message' => __('Invalid post ID.', 'palgoals-core')]);
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'pg_food_menu') {
        wp_send_json_error(['message' => __('Post not found.', 'palgoals-core')]);
    }

    $categories = wp_get_post_terms($post_id, 'pg_food_menu_category');
    $category_id = $categories ? $categories[0]->term_id : 0;

    wp_send_json_success([
        'title' => $post->post_title,
        'description' => $post->post_content,
        'price' => get_post_meta($post_id, '_food_price', true),
        'category' => $category_id,
        'image_url' => wp_get_attachment_url(get_post_thumbnail_id($post_id)),
        'image_id' => get_post_thumbnail_id($post_id)
    ]);
}

// تحديث بيانات العنصر
add_action('wp_ajax_update_food_menu_item', 'update_food_menu_item');
function update_food_menu_item() {
    parse_str($_POST['data'], $data);

    $post_id = intval($data['post_id']);
    if (!$post_id) {
        wp_send_json_error(['message' => __('Invalid post ID.', 'palgoals-core')]);
    }

    $post_data = [
        'ID' => $post_id,
        'post_title' => sanitize_text_field($data['food_title']),
        'post_content' => sanitize_textarea_field($data['food_description'])
    ];
    wp_update_post($post_data);

    // تحديث الميتا والحقول
    update_post_meta($post_id, '_food_price', floatval($data['food_Price']));
    wp_set_post_terms($post_id, intval($data['parent']), 'pg_food_menu_category');
    set_post_thumbnail($post_id, intval($data['food_image']));

    wp_send_json_success(['message' => __('Food item updated successfully.', 'palgoals-core')]);
}


add_action('admin_post_update_food', function () {
    // التحقق من صحة الطلب
    if (!isset($_POST['update_food_nonce']) || !wp_verify_nonce($_POST['update_food_nonce'], 'update_food')) {
        wp_die(__('Invalid nonce', 'palgoals-core'));
    }

    // التحقق من وجود معرف المنشور
    $food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;
    if (!$food_id || get_post_type($food_id) !== 'pg_food_menu') {
        wp_die(__('Invalid food ID', 'palgoals-core'));
    }

    // تحديث البيانات النصية
    if (isset($_POST['food_title'])) {
        wp_update_post([
            'ID' => $food_id,
            'post_title' => sanitize_text_field($_POST['food_title']),
            'post_content' => sanitize_textarea_field($_POST['food_description']),
        ]);
    }

    // تحديث السعر
    if (isset($_POST['food_price'])) {
        update_post_meta($food_id, '_pg_food_menu_price', sanitize_text_field($_POST['food_price']));
    }

    // تحديث التصنيفات
    if (isset($_POST['food_category'])) {
        $categories = array_map('intval', (array) $_POST['food_category']);
        wp_set_post_terms($food_id, $categories, 'pg_food_menu_category');
    }

    // تحديث الصورة البارزة
    if (isset($_POST['image_id']) && is_numeric($_POST['image_id'])) {
        $image_id = intval($_POST['image_id']);
        set_post_thumbnail($food_id, $image_id);
    }

    // إعادة التوجيه بعد الحفظ
    wp_redirect(add_query_arg('updated', 'true', admin_url('admin.php?page=edit_food&id=' . $food_id)));
    exit;
});
