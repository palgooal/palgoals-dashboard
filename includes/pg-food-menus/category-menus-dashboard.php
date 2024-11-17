<?php
// تسجيل قاعدة إعادة الكتابة للصفحة المخصصة
function palgoals_menus_category_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-category-menus/?$', 'index.php?pg_category_menus=true', 'top');
}
add_action('init', 'palgoals_menus_category_dashboard_rewrite_rule');

// إضافة متغير جديد للصفحة المخصصة
function palgoals_menus_category_query_vars($vars) {
    $vars[] = 'pg_category_menus';
    return $vars;
}
add_filter('query_vars', 'palgoals_menus_category_query_vars');

// عرض صفحة التصنيفات المخصصة
function palgoals_menus_category_dashboard_page() {
    if (get_query_var('pg_category_menus')) {
        if (is_user_logged_in()) {
            include dirname(__DIR__, 2) . '/templates/menus/category-menus.php';
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_menus_category_dashboard_page');

// تعطيل ملفات CSS الخاصة بالثيم
function palgoals_remove_theme_category_menus() {
    if (get_query_var('pg_category_menus')) {
        palgoals_enqueue_shared_assets();
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (isset($wp_styles->registered[$handle]) && strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_category_menus', 99);

// إضافة حقل لرفع صورة للتصنيفات
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
add_action('pg_food_menu_category_edit_form_fields', 'add_category_image_field');
add_action('pg_food_menu_category_add_form_fields', 'add_category_image_field');

// حفظ صورة التصنيف
function save_category_image($term_id) {
    if (isset($_POST['category_image'])) {
        update_term_meta($term_id, 'category_image', sanitize_text_field($_POST['category_image']));
    }
}
add_action('edited_pg_food_menu_category', 'save_category_image');
add_action('created_pg_food_menu_category', 'save_category_image');

function enqueue_category_image_script($hook_suffix) {
    if ('edit-tags.php' === $hook_suffix && isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'pg_food_menu_category') {
        wp_enqueue_media();
        wp_enqueue_script('category-image-script', plugin_dir_url(__FILE__) . 'js/category-image.js', ['jquery'], null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_category_image_script');

