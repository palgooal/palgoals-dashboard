<?php
function palgoals_register_food_menu_taxonomies() {
    register_taxonomy('pg_food_menu_category', 'pg_food_menu', [
        'labels' => [
            'name' => __('Categories', 'palgoals-dash'),
            'add_new_item' => __('Add New Category', 'palgoals-dash'),
        ],
        'hierarchical' => true,
        'show_ui' => true,
    ]);
}
add_action('init', 'palgoals_register_food_menu_taxonomies');

// إضافة حقل الصورة إلى صفحة تعديل التصنيف
function add_category_image_field($term) {
    $image_id = get_term_meta($term->term_id, 'category_image', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="category_image"><?php esc_html_e('Category Image', 'palgoals-dash'); ?></label>
        </th>
        <td>
            <input type="hidden" id="category_image" name="category_image" value="<?php echo esc_attr($image_id); ?>">
            <div id="category-image-preview" style="margin-bottom: 10px;">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 150px; height: auto;">
                <?php endif; ?>
            </div>
            <button type="button" class="button" id="upload-category-image"><?php esc_html_e('Upload Image', 'palgoals-dash'); ?></button>
            <button type="button" class="button button-secondary" id="remove-category-image"><?php esc_html_e('Remove Image', 'palgoals-dash'); ?></button>
        </td>
    </tr>
    <?php
}
add_action('pg_food_menu_category_edit_form_fields', 'add_category_image_field', 10, 1);

// إضافة حقل الصورة إلى صفحة إضافة التصنيف
function add_category_image_field_on_add() {
    ?>
<form id="addtag" method="post">
    <div class="form-field">
        <label for="category_image"><?php esc_html_e('Category Image', 'palgoals-dash'); ?></label>
        <input type="hidden" id="category_image" name="category_image" value="">
        <div id="category-image-preview"></div>
        <button type="button" id="upload-category-image" class="button"><?php esc_html_e('Upload Image', 'palgoals-dash'); ?></button>
        <button type="button" id="remove-category-image" class="button button-secondary"><?php esc_html_e('Remove Image', 'palgoals-dash'); ?></button>
    </div>
    <button type="submit" class="button button-primary"><?php esc_html_e('Add Category', 'palgoals-dash'); ?></button>
</form>

    <?php
}
add_action('pg_food_menu_category_add_form_fields', 'add_category_image_field_on_add');

// حفظ الصورة عند الحفظ أو التعديل
function save_category_image($term_id) {
    if (isset($_POST['category_image'])) {
        update_term_meta($term_id, 'category_image', sanitize_text_field($_POST['category_image']));
    }
}
add_action('edited_pg_food_menu_category', 'save_category_image');
add_action('created_pg_food_menu_category', 'save_category_image');

function hide_default_add_category_button() {
    echo '<style>
        #addtag .submit input.button.button-primary {
            display: none !important;
        }
            input#submit {
    display: none;
}

    </style>';
}
add_action('admin_head-edit-tags.php', 'hide_default_add_category_button');
