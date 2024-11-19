<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تضمين الهيدر
include plugin_dir_path(__DIR__) . '/partials/header.php';

// جلب جميع التصنيفات
$categories = get_terms([
    'taxonomy' => 'pg_food_menu_category',
    'hide_empty' => false, // عرض جميع التصنيفات حتى لو لم تحتوي على منشورات
]);
?>

<!-- [ Main Content ] -->
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php esc_html_e('Food Menu Categories', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] -->

        <!-- [ Main Content Section ] -->
        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card table-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="sm:flex items-center justify-between">
                            <h5 class="mb-3 sm:mb-0"><?php esc_html_e('All Categories', 'palgoals-dash'); ?></h5>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-hover" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Name', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Description', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Slug', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Posts Count', 'palgoals-dash'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                                        <?php foreach ($categories as $category) : ?>
                                            <tr>
                                                <td><?php echo esc_html($category->name); ?></td>
                                                <td><?php echo esc_html($category->description); ?></td>
                                                <td><?php echo esc_html($category->slug); ?></td>
                                                <td><?php echo esc_html($category->count); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4" class="text-center"><?php esc_html_e('No Categories Found.', 'palgoals-dash'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content Section ] -->
    </div>
</div>
<!-- [ Main Content ] -->
<a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=pg_food_menu_category')); ?>" class="btn btn-secondary">
    <?php esc_html_e('Manage Categories', 'palgoals-dash'); ?>
</a>


<?php include plugin_dir_path(__DIR__). '/partials/footer.php';?>
