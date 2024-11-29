<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تضمين الهيدر
include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/header.php';

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
                            <button class="btn btn-primary mt-1 mx-1" type="button" data-pc-toggle="offcanvas" data-pc-target="#offcanvasExample" aria-controls="offcanvasExample">
                                <?php esc_html_e('Add Categories', 'palgoals-dash'); ?>
                            </button>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-hover" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Image', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Name', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Description', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Posts Count', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Actions', 'palgoals-dash'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                                    <?php foreach ($categories as $category) : 
                                        $image_id = get_term_meta($category->term_id, 'category_image', true);
                                        $image_url = $image_id ? wp_get_attachment_url($image_id) : 'https://via.placeholder.com/150';
                                    ?>
                                    <tr>
                                        <!-- Image -->
                                        <td><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-16 h-16"></td>
                                        <!-- Name -->
                                        <td><?php echo esc_html($category->name); ?></td>
                                        <!-- Description -->
                                        <td><?php echo esc_html($category->description); ?></td>
                                        <!-- Posts Count -->
                                        <td><?php echo esc_html($category->count); ?></td>
                                        <!-- Actions -->
                                        <td>
                                            <a href="<?php echo esc_url(get_term_link($category->term_id, 'pg_food_menu_category')); ?>" target="_blank" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                <i class="ti ti-eye text-xl leading-none"></i>
                                            </a>
                                            <?php $a= $category->term_id;?>
                                            <a href="edit-category/?id=<?php echo $a; ?>" target="_blank" class="btn-link-secondary">
                                                <i class="ti ti-edit text-xl leading-none"></i>
                                            </a>
                                            <a href="<?php echo $category->term_id; ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary delete-category" data-category-id="<?php echo esc_attr($category->term_id); ?>">
                                                <i class="ti ti-trash text-xl leading-none"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center"><?php esc_html_e('No Food Menus Found.', 'palgoals-core'); ?></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/footer.php'; ?>
<?php include plugin_dir_path(__DIR__). 'taxonomies/add-categories.php'; ?>