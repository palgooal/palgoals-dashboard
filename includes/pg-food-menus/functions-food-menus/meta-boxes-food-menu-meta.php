<?php
function palgoals_add_price_meta_box() {
    add_meta_box('pg_food_menu_price', __('Food Menu Price', 'palgoals-dash'), 'palgoals_render_price_meta_box', 'pg_food_menu', 'side', 'default');
}

function palgoals_render_price_meta_box($post) {
    $price = get_post_meta($post->ID, '_pg_food_menu_price', true);
    ?>
    <label><?php _e('Price:', 'palgoals-dash'); ?></label>
    <input type="number" name="pg_food_menu_price" value="<?php echo esc_attr($price); ?>" step="0.01">
    <?php
}

function palgoals_save_price_meta_box($post_id) {
    if (isset($_POST['pg_food_menu_price'])) {
        update_post_meta($post_id, '_pg_food_menu_price', sanitize_text_field($_POST['pg_food_menu_price']));
    }
}
add_action('add_meta_boxes', 'palgoals_add_price_meta_box');
add_action('save_post', 'palgoals_save_price_meta_box');
