<?php
function palgoals_menus_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-menus/?$', 'index.php?pg_menus=true', 'top');
}
add_action('init', 'palgoals_menus_dashboard_rewrite_rule');

function palgoals_menus_query_vars($vars) {
    $vars[] = 'pg_menus';
    return $vars;
}
add_filter('query_vars', 'palgoals_menus_query_vars');

function palgoals_menus_dashboard_page() {
    if (get_query_var('pg_menus')) {
        if (is_user_logged_in()) {
            include dirname(__DIR__, 2) . '/templates/menus/pg-menus.php';
            
        } else {
            wp_safe_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_menus_dashboard_page');

function palgoals_enqueue_menus_dashboard_assets() {
    if (get_query_var('pg_menus')) {
        palgoals_enqueue_shared_assets();
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_menus_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_remove_theme_menus() {
    if (get_query_var('pg_menus')) {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (isset($wp_styles->registered[$handle]) && strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_menus', 99);


function palgoals_register_food_menu_post_type() {
    register_post_type('pg_food_menu', [
        'labels' => [
            'name' => 'قوائم الطعام',
            'singular_name' => 'قائمة الطعام',
            'add_new' => 'إضافة قائمة جديدة',
            'add_new_item' => 'إضافة قائمة طعام جديدة',
            'edit_item' => 'تحرير قائمة الطعام',
            'new_item' => 'قائمة طعام جديدة',
            'view_item' => 'عرض قائمة الطعام',
            'all_items' => 'جميع قوائم الطعام',
            'search_items' => 'بحث عن قوائم الطعام',
        ],
        'public' => true,
        'show_ui' => true,
        'menu_icon' => 'dashicons-carrot',
        'supports' => ['title', 'editor', 'thumbnail'], // دعم الصور المميزة
    ]);
}
add_action('init', 'palgoals_register_food_menu_post_type');

function palgoals_register_food_menu_taxonomies() {
    // تسجيل تصنيفات (Categories)
    register_taxonomy(
        'pg_food_menu_category', // معرف التصنيف
        'pg_food_menu',          // نوع المنشور الذي يرتبط به التصنيف
        [
            'labels' => [
                'name' => __('Categories', 'palgoals-dash'),
                'singular_name' => __('Category', 'palgoals-dash'),
                'search_items' => __('Search Categories', 'palgoals-dash'),
                'all_items' => __('All Categories', 'palgoals-dash'),
                'parent_item' => __('Parent Category', 'palgoals-dash'),
                'parent_item_colon' => __('Parent Category:', 'palgoals-dash'),
                'edit_item' => __('Edit Category', 'palgoals-dash'),
                'update_item' => __('Update Category', 'palgoals-dash'),
                'add_new_item' => __('Add New Category', 'palgoals-dash'),
                'new_item_name' => __('New Category Name', 'palgoals-dash'),
                'menu_name' => __('Categories', 'palgoals-dash'),
            ],
            'hierarchical' => true, // مثل الفئات (Categories)
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'food-menu-category'],
        ]
    );

    // تسجيل علامات (Tags)
    register_taxonomy(
        'pg_food_menu_tag', // معرف التصنيف
        'pg_food_menu',     // نوع المنشور
        [
            'labels' => [
                'name' => __('Tags', 'palgoals-dash'),
                'singular_name' => __('Tag', 'palgoals-dash'),
                'search_items' => __('Search Tags', 'palgoals-dash'),
                'all_items' => __('All Tags', 'palgoals-dash'),
                'edit_item' => __('Edit Tag', 'palgoals-dash'),
                'update_item' => __('Update Tag', 'palgoals-dash'),
                'add_new_item' => __('Add New Tag', 'palgoals-dash'),
                'new_item_name' => __('New Tag Name', 'palgoals-dash'),
                'menu_name' => __('Tags', 'palgoals-dash'),
            ],
            'hierarchical' => false, // مثل العلامات (Tags)
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'food-menu-tag'],
        ]
    );
}
add_action('init', 'palgoals_register_food_menu_taxonomies');


function palgoals_add_price_meta_box() {
    add_meta_box(
        'pg_food_menu', // معرف الحقل
        'سعر البوست الصغير', // العنوان
        'palgoals_render_price_meta_box', // دالة العرض
        'pg_food_menu', // نوع المنشور
        'side', // مكان الميتا بوكس
        'default' // الأولوية
    );
}
add_action('add_meta_boxes', 'palgoals_add_price_meta_box');

function palgoals_render_price_meta_box($post) {
    $price = get_post_meta($post->ID, '_pg_food_menu_price', true);
    $currency = get_post_meta($post->ID, '_pg_food_menu_currency', true);

    $currencies = [
        'SAR' => 'ريال سعودي',
        'AED' => 'درهم إماراتي',
        'KWD' => 'دينار كويتي',
        'BHD' => 'دينار بحريني',
        'OMR' => 'ريال عماني',
        'TRY' => 'ليرة تركية',
        'EGP' => 'جنيه مصري',
        'ILS' => 'شيكل إسرائيلي',
        'JOD' => 'دينار أردني',
        'USD' => 'دولار أمريكي',
    ];
    ?>
    <label for="pg_food_menu_price">السعر:</label>
    <input type="number" id="pg_food_menu_price" name="pg_food_menu_price" value="<?php echo esc_attr($price); ?>" class="widefat" step="0.01" min="0">

    <label for="pg_food_menu_currency" style="margin-top: 10px; display: block;">العملة:</label>
    <select id="pg_food_menu_currency" name="pg_food_menu_currency" class="widefat">
        <option value=""><?php esc_html_e('Select Currency', 'palgoals-dash'); ?></option>
        <?php foreach ($currencies as $code => $label) : ?>
            <option value="<?php echo esc_attr($code); ?>" <?php selected($currency, $code); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}






function palgoals_save_price_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['pg_food_menu_price'])) {
        $price = sanitize_text_field($_POST['pg_food_menu_price']);
        update_post_meta($post_id, '_pg_food_menu_price', $price);
    }

    if (isset($_POST['pg_food_menu_currency'])) {
        $currency = sanitize_text_field($_POST['pg_food_menu_currency']);
        update_post_meta($post_id, '_pg_food_menu_currency', $currency);
    }
}
add_action('save_post', 'palgoals_save_price_meta_box');






