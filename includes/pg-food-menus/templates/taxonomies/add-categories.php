<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><?php esc_html_e('Add New Category', 'palgoals-dash'); ?></h5>
        <button data-pc-dismiss="#offcanvasExample" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
            <i class="ti ti-x"></i>
        </button>
    </div>
    <div class="offcanvas-body customer-body">
        <div class="card-body">
        <form id="add-category-form">
    <!-- Name -->
    <div class="mb-3">
        <label for="category-name">Name</label>
        <input type="text" id="category-name" name="name" class="form-control" required>
    </div>
    <!-- Slug -->
    <div class="mb-3">
        <label for="category-slug">Slug</label>
        <input type="text" id="category-slug" name="slug" class="form-control">
    </div>
    <!-- Parent -->
    <div class="mb-3">
        <label for="parent-category">Parent Category</label>
        <select id="parent-category" name="parent" class="form-control">
    <option value="0"><?php esc_html_e('None', 'palgoals-dash'); ?></option>
    <?php
    $categories = get_terms([
        'taxonomy' => 'pg_food_menu_category',
        'hide_empty' => false,
    ]);

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
        }
    } else {
        echo '<option value="">' . esc_html__('No Categories Found', 'palgoals-dash') . '</option>';
    }
    ?>
</select>
    </div>
    <!-- Description -->
    <div class="mb-3">
        <label for="category-description"><?php _e('Description', 'palgoals-core'); ?></label>
        <textarea id="category-description" name="description" class="form-control"></textarea>
    </div>
    <!-- Image -->
    <div class="mb-3">
        <label><?php _e('Category Image', 'palgoals-core'); ?></label>
        <div id="category-image-preview" class="mb-2"></div>
        <button type="button" id="upload-category-image" class="btn btn-secondary"><?php _e('Upload Image', 'palgoals-core'); ?></button>
        <input type="hidden" id="category_image" name="image_id">
    </div>
    <!-- Submit -->
    <button type="submit" class="btn btn-primary">Add Category</button>
</form>

        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
    $slug = isset($_GET['slug']) ? sanitize_title($_GET['slug']) : '';
    $parent = isset($_GET['parent']) ? intval($_GET['parent']) : 0;
    $description = isset($_GET['description']) ? sanitize_textarea_field($_GET['description']) : '';
    $image_id = isset($_GET['image_id']) ? intval($_GET['image_id']) : 0;

    if ($name) {
        $term = wp_insert_term($name, 'pg_food_menu_category', [
            'slug' => $slug,
            'parent' => $parent,
            'description' => $description,
        ]);

        if (!is_wp_error($term)) {
            if ($image_id) {
                update_term_meta($term['term_id'], 'category_image', $image_id);
            }
            echo '<p>Category Added Successfully: ' . esc_html($term['term_id']) . '</p>';
        } else {
            echo '<p>Error: ' . esc_html($term->get_error_message()) . '</p>';
        }
    } else {
        echo '<p>No Name Provided</p>';
    }
}
?>