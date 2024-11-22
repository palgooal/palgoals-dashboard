<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><?php esc_html_e('Add New Category', 'palgoals-dash'); ?></h5>
        <button data-pc-dismiss="#offcanvasExample" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
            <i class="ti ti-x"></i>
        </button>
    </div>
    <div class="offcanvas-body customer-body">
        <div class="card-body">
            <form id="add-category-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label"><?php esc_html_e('Name:', 'palgoals-dash'); ?></label>
                    <input type="text" id="category-name" class="form-control" placeholder="<?php esc_html_e('Enter category name', 'palgoals-dash'); ?>" />
                    <small class="form-text text-muted"><?php esc_html_e('The name is how it appears on your site.', 'palgoals-dash'); ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php esc_html_e('Slug', 'palgoals-dash'); ?></label>
                    <input type="text" id="category-slug" class="form-control" placeholder="<?php esc_html_e('Enter slug', 'palgoals-dash'); ?>" />
                    <small class="form-text text-muted"><?php esc_html_e('The “slug” is the URL-friendly version of the name.', 'palgoals-dash'); ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="parent-category"><?php esc_html_e('Parent Category', 'palgoals-dash'); ?></label>
                    <select class="form-select" id="parent-category">
                        <option value="0"><?php esc_html_e('None', 'palgoals-dash'); ?></option>
                        <?php
                        // Populate parent categories dynamically
                        $categories = get_terms(array(
                            'taxonomy'   => 'menu_category',
                            'hide_empty' => false,
                        ));
                        foreach ($categories as $category) {
                            echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category-description"><?php esc_html_e('Description', 'palgoals-dash'); ?></label>
                    <textarea id="category-description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php esc_html_e('Category Image', 'palgoals-dash'); ?></label>
                    <input type="file" id="category-image" class="form-control">
                </div>
            </form>
        </div>
        <div class="text-end">
            <button id="add-category-btn" class="btn btn-light-danger btn-sm"><?php esc_html_e('Add Category', 'palgoals-dash'); ?></button>
            <button class="btn btn-light-danger btn-sm" data-pc-dismiss="#offcanvasExample"><?php esc_html_e('Close', 'palgoals-dash'); ?></button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function ($) {
    $('#add-category-btn').on('click', function (e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('action', 'palgoals_add_menu_category');
        formData.append('name', $('#category-name').val());
        formData.append('slug', $('#category-slug').val());
        formData.append('parent', $('#parent-category').val());
        formData.append('description', $('#category-description').val());
        formData.append('image', $('#category-image')[0].files[0]);
        formData.append('nonce', '<?php echo wp_create_nonce('add_category_nonce'); ?>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert('<?php esc_html_e('Category added successfully!', 'palgoals-dash'); ?>');
                    location.reload();
                } else {
                    alert(response.data.message || '<?php esc_html_e('An error occurred.', 'palgoals-dash'); ?>');
                }
            },
            error: function () {
                alert('<?php esc_html_e('Request failed. Please try again.', 'palgoals-dash'); ?>');
            },
        });
    });
});
</script>
