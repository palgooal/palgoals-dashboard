<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تأكد من تمرير المعرف
$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($food_id) {
    // جلب البيانات الخاصة بالعنصر
    $food_post = get_post($food_id);

    if ($food_post && $food_post->post_type === 'pg_food_menu') {
        $food_title = $food_post->post_title;
        $food_price = get_post_meta($food_id, '_pg_food_menu_price', true);
        $food_description = $food_post->post_content;
        $food_image_id = get_post_meta($food_id, '_food_image', true);

        // جلب جميع التصنيفات
        $categories = get_terms([
            'taxonomy' => 'pg_food_menu_category',
            'hide_empty' => false,
        ]);

        // جلب التصنيفات المرتبطة بالمنشور
        $food_category = wp_get_post_terms($food_id, 'pg_food_menu_category', ['fields' => 'ids']);

        // إعداد رابط الصورة وعنصر <img>
        $food_image_html = $food_image_id ? wp_get_attachment_image($food_image_id, 'thumbnail', false, ['class' => 'img-thumbnail']) : '<p>' . __('No image uploaded', 'palgoals-core') . '</p>';
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
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('update_food', 'update_food_nonce'); ?>
                            <input type="hidden" name="action" value="update_food">
                            <input type="hidden" name="food_id" value="<?php echo esc_attr($food_id); ?>">

                            <div class="grid grid-cols-12 gap-x-6">
                                <!-- Title Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-title" class="form-label"><?php _e('Food Title', 'palgoals-core'); ?></label>
                                        <input type="text" name="food_title" id="food-title" class="form-control" value="<?php echo esc_attr($food_title); ?>"/>
                                    </div>
                                </div>

                                <!-- Price Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-price" class="form-label"><?php _e('Food Price', 'palgoals-core'); ?></label>
                                        <input type="number" name="food_price" id="food-price" class="form-control" value="<?php echo esc_attr($food_price); ?>"/>
                                    </div>
                                </div>

                                <!-- Description Input -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="food-description" class="form-label"><?php _e('Food Description', 'palgoals-core'); ?></label>
                                        <textarea name="food_description" id="food-description" class="form-control" required><?php echo esc_html($food_description); ?></textarea>
                                    </div>
                                </div>

                                <!-- Category Selection -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="parent-category" class="form-label"><?php _e('Category', 'palgoals-core'); ?></label>
                                        <select id="parent-category" name="food_category[]" class="form-control" multiple>
                                            <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array($category->term_id, $food_category) ? 'selected' : ''; ?>>
                                                        <?php echo esc_html($category->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
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
