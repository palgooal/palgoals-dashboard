<?php
add_action('wp_ajax_palgoals_add_menu_category', 'palgoals_add_menu_category');
function palgoals_add_menu_category() {
    check_ajax_referer('add_category_nonce', 'nonce');

    $name = sanitize_text_field($_POST['name']);
    $slug = sanitize_title($_POST['slug']);
    $parent = intval($_POST['parent']);
    $description = sanitize_textarea_field($_POST['description']);

    // Insert category
    $term = wp_insert_term($name, 'pg_food_menu_category', [
        'slug' => $slug,
        'parent' => $parent,
        'description' => $description,
    ]);

    if (is_wp_error($term)) {
        wp_send_json_error(['message' => $term->get_error_message()]);
    } else {
        wp_send_json_success(['term_id' => $term['term_id']]);
    }
}
