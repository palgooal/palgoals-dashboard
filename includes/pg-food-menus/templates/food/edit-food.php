<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// التعامل مع البيانات المرسلة عبر POST (عملية الحفظ)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_food_nonce'])) {
    // تحقق من صحة nonce للحماية
    if (!wp_verify_nonce($_POST['update_food_nonce'], 'update_food')) {
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

        // تحديث الصورة البارزة
        if (!empty($_POST['image_id'])) {
            set_post_thumbnail($food_id, intval($_POST['image_id']));
        } else {
            delete_post_thumbnail($food_id);
        }

        // تحديث البيانات الوصفية
        update_post_meta($food_id, '_pg_food_menu_price', sanitize_text_field($_POST['food_price']));

        // تحديث التصنيفات
        if (isset($_POST['food_category'])) {
            wp_set_post_terms($food_id, $_POST['food_category'], 'pg_food_menu_category');
        }

        // إعادة التوجيه مع رسالة نجاح
        wp_redirect(add_query_arg(['id' => $food_id, 'updated' => 'true'], home_url('/dashboard/pg-menus/edit-food/')));
        exit;
    } else {
        wp_die(__('Invalid food ID', 'palgoals-core'));
    }
}

// التعامل مع الطلبات العادية (GET) لعرض الصفحة
$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($food_id) {
    // جلب بيانات المنشور
    $food_post = get_post($food_id);

    if ($food_post && $food_post->post_type === 'pg_food_menu') {
        $food_title       = $food_post->post_title;
        $food_price       = get_post_meta($food_id, '_pg_food_menu_price', true);
        $food_description = $food_post->post_content;

        // جلب التصنيفات
        $categories    = get_terms(['taxonomy' => 'pg_food_menu_category', 'hide_empty' => false]);
        $food_category = wp_get_post_terms($food_id, 'pg_food_menu_category', ['fields' => 'ids']);
    } else {
        wp_die(__('Invalid food ID or post type', 'palgoals-core'));
    }
} else {
    wp_die(__('No food ID provided', 'palgoals-core'));
}

// تضمين الهيدر
include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/header.php';
?>

<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php _e('Edit Food', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php _e('Edit Food', 'palgoals-dash'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true') : ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php _e('Food item updated successfully!', 'palgoals-core'); ?></p>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo esc_url(home_url('/dashboard/pg-menus/edit-food/')); ?>">
                            <?php wp_nonce_field('update_food', 'update_food_nonce'); ?>
                            <input type="hidden" name="food_id" value="<?php echo esc_attr($food_id); ?>">

                            <div class="grid grid-cols-12 gap-x-6">
                                <!-- Title Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-title" class="form-label"><?php _e('Food Title', 'palgoals-core'); ?></label>
                                        <input type="text" name="food_title" id="food-title" class="form-control" value="<?php echo esc_attr($food_title); ?>" />
                                    </div>
                                </div>

                                <!-- Price Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-price" class="form-label"><?php _e('Food Price', 'palgoals-core'); ?></label>
                                        <input type="number" name="food_price" id="food-price" class="form-control" value="<?php echo esc_attr($food_price); ?>" />
                                    </div>
                                </div>

                                <!-- Description Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-description" class="form-label"><?php _e('Food Description', 'palgoals-core'); ?></label>
                                        <textarea name="food_description" id="food-description" class="form-control"><?php echo esc_html($food_description); ?></textarea>
                                    </div>
                                </div>

                                <!-- Category Selection -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="parent-category" class="form-label"><?php _e('Category', 'palgoals-core'); ?></label>
                                        <select id="parent-category" name="food_category[]" class="form-control" multiple>
                                            <?php if (!empty($categories)) : ?>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array($category->term_id, $food_category) ? 'selected' : ''; ?>>
                                                        <?php echo esc_html($category->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <option value=""><?php _e('No categories found', 'palgoals-core'); ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Food Image Upload -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label><?php _e('Food Image', 'palgoals-core'); ?></label>
                                        <div id="food-image-preview" class="mb-2">
                                            <?php
                                            // التأكد من وجود صورة بارزة
                                            if (has_post_thumbnail($food_id)) {
                                                echo get_the_post_thumbnail($food_id, 'thumbnail', ['class' => 'img-thumbnail']);
                                                } else {
                                                    echo '<p>' . __('No image uploaded', 'palgoals-core') . '</p>';
                                                }
                                            ?>
                                        </div>
                                        <button type="button" id="upload-food-image" class="btn btn-secondary">
                                            <?php _e('Upload Image', 'palgoals-core'); ?>
                                        </button>
                                        <input type="hidden" id="food_image" name="image_id" value="<?php echo esc_attr(get_post_thumbnail_id($food_id)); ?>">
                                    </div>
                                </div>




                                <!-- Submit Button -->
                                <div class="col-span-12 text-right">
                                    <button type="submit" class="btn btn-primary"><?php _e('Update Food', 'palgoals-core'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<?php include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/footer.php'; ?>
