<div id="animateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="animateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('Add New Food', 'palgoals-dash'); ?></h5>
                <button data-pc-modal-dismiss="#animateModal" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                    <i class="ti ti-x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="food-title" class="form-label"><?php _e('Add Title', 'palgoals-core'); ?></label>
                            <input type="text" name="food_title" id="food-title" class="form-control" required placeholder="<?php _e('Enter the Food name', 'palgoals-core'); ?>" />
                        </div>

                        <div class="mb-3">
                            <label for="food-description" class="form-label"><?php _e('Food Description', 'palgoals-core'); ?></label>
                            <textarea type="text" name="food-description" id="food-description" class="form-control" required placeholder="<?php _e('Write a brief description of the food here.', 'palgoals-core'); ?>"></textarea>
                        </div>

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6 mb-4">
                                <label for="food-Price" class="form-label"><?php _e('Food Price', 'palgoals-core'); ?></label>
                                <input type="number" name="food_Price" id="food-Price" class="form-control" required placeholder="<?php _e('Add Food Price', 'palgoals-core'); ?>" />
                            </div>

                            <div class="col-span-12 md:col-span-6 mb-4">
                                <label for="parent-category" class="form-label"><?php _e('Category', 'palgoals-core'); ?></label>
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
                        </div>

                        <div class="mb-3">
                            <label><?php _e('Food Image', 'palgoals-core'); ?></label>
                            <div id="Food-image-preview" class="mb-2"></div>
                            <button type="button" id="upload-food-image" class="btn btn-secondary"><?php _e('Upload Image', 'palgoals-core'); ?></button>
                            <input type="hidden" id="food_image" name="image_id">
                        </div>

                        <input type="hidden" id="redirect-url" name="redirect_url" value="<?php echo esc_url(get_site_url()); ?>" />
                    </form>
                </div>
            </div>

            <div class="modal-footer gap-4">
                <button id="create-page" class="btn btn-primary ltr:ml-2 trl:mr-2"><?php _e('Add', 'palgoals-core'); ?></button>
                <button class="btn btn-secondary" data-pc-modal-dismiss="#animateModal"><?php _e('Closing', 'palgoals-core'); ?></button>
            </div>
        </div>
    </div>
</div>