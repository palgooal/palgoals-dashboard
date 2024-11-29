<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_food_nonce'])) {
    // تحقق من nonce للحماية
    if (!wp_verify_nonce($_POST['update_food_nonce'], 'update_food')) {
        wp_die(__('Security check failed.', 'palgoals-dashe'));
    }

    // جلب بيانات التصنيف
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

    if (!$category_id) {
        wp_die(__('No category ID provided.', 'palgoals-dashe'));
    }

    // إعداد البيانات للحفظ
    $name        = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $slug        = isset($_POST['slug']) ? sanitize_title($_POST['slug']) : '';
    $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
    $parent      = isset($_POST['parent']) ? intval($_POST['parent']) : 0;
    $image_id    = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;

    // تحديث التصنيف
    $result = wp_update_term($category_id, 'pg_food_menu_category', [
        'name'        => $name,
        'slug'        => $slug,
        'description' => $description,
        'parent'      => $parent,
    ]);

    if (is_wp_error($result)) {
        wp_die($result->get_error_message());
    }

    // تحديث الصورة الوصفية
    if ($image_id) {
        update_term_meta($category_id, 'category_image', $image_id);
    } else {
        delete_term_meta($category_id, 'category_image');
    }

    // إعادة التوجيه مع رسالة نجاح
    wp_redirect(add_query_arg(['id' => $category_id, 'updated' => 'true'], home_url('/dashboard/pg-category-menus/edit-category/')));
    exit;
}



// جلب معرف التصنيف (category ID)
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($category_id) {
    // جلب بيانات التصنيف
    $category = get_term($category_id, 'pg_food_menu_category');

    if ($category && !is_wp_error($category)) {
        $category_name        = $category->name;
        $category_slug        = $category->slug;
        $category_description = $category->description;
        $parent_id            = $category->parent;
        $category_image_id    = get_term_meta($category_id, 'category_image', true);
    } else {
        wp_die(__('Invalid category ID or taxonomy.', 'palgoals-dashe'));
    }
} else {
    wp_die(__('No category ID provided.', 'palgoals-dashe'));
}

// تضمين الهيدر
include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/header.php';
?>

<div class="pc-container">
    <div class="pc-content">
        <!-- [ Breadcrumb ] -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php _e('Edit Category', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>

        <!-- [ Main Content ] -->
        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php _e('Edit Category', 'palgoals-dash'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true') : ?>
                            <div class="alert alert-success" role="alert">
                                <p><?php _e('Category updated successfully!', 'palgoals-dashe'); ?></p>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo esc_url(home_url('/dashboard/pg-category-menus/edit-category/')); ?>">
                            <?php wp_nonce_field('update_food', 'update_food_nonce'); ?>
                            <input type="hidden" name="category_id" value="<?php echo esc_attr($category_id); ?>">

                            <div class="grid grid-cols-12 gap-x-6">
                                <!-- Category Name -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="category-name" class="form-label"><?php esc_html_e('Name', 'palgoals-dash'); ?></label>
                                        <input type="text" name="name" id="category-name" class="form-control" value="<?php echo esc_attr($category_name); ?>" />
                                    </div>
                                </div>

                                <!-- Category Slug -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="category-slug" class="form-label"><?php esc_html_e('Slug', 'palgoals-dash'); ?></label>
                                        <input type="text" name="slug" id="category-slug" class="form-control" value="<?php echo esc_attr($category_slug); ?>" />
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="category-description" class="form-label"><?php esc_html_e('Description', 'palgoals-dashe'); ?></label>
                                        <textarea name="description" id="category-description" class="form-control"><?php echo esc_textarea($category_description); ?></textarea>
                                    </div>
                                </div>

                                <!-- Parent Category -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="parent-category" class="form-label"><?php esc_html_e('Parent Category', 'palgoals-dash'); ?></label>
                                        <select id="parent-category" name="parent" class="form-control">
                                            <option value="0"><?php _e('None', 'palgoals-dashe'); ?></option>
                                            <?php
                                            $categories = get_terms([
                                                'taxonomy' => 'pg_food_menu_category',
                                                'hide_empty' => false,
                                            ]);

                                            if (!empty($categories)) {
                                                foreach ($categories as $cat) {
                                                    echo '<option value="' . esc_attr($cat->term_id) . '"' . selected($parent_id, $cat->term_id, false) . '>' . esc_html($cat->name) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">' . esc_html__('No categories found', 'palgoals-dashe') . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Category Image -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label><?php _e('Category Image', 'palgoals-dashe'); ?></label>
                                        <div id="food-image-preview" class="mb-2">
                                            <?php
                                            if ($category_image_id) {
                                                echo wp_get_attachment_image($category_image_id, 'thumbnail', false, ['class' => 'img-thumbnail']);
                                            } else {
                                                echo '<p>' . __('No image uploaded', 'palgoals-dashe') . '</p>';
                                            }
                                            ?>
                                        </div>
                                        <button type="button" id="upload-food-image" class="btn btn-secondary">
                                            <?php _e('Upload Image', 'palgoals-dashe'); ?>
                                        </button>
                                        <input type="hidden" id="category_image" name="image_id" value="<?php echo esc_attr($category_image_id); ?>">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-span-12 text-right">
                                    <button type="submit" class="btn btn-primary"><?php _e('Update Category', 'palgoals-dashe'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/footer.php'; ?>
