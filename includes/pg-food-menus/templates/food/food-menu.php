<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تضمين الهيدر
include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/header.php';

// التحقق من الصفحة الحالية
$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

// إعداد الاستعلام لجلب المنشورات
$args = [
    'post_type'      => 'pg_food_menu',
    'posts_per_page' => 5,
    'paged'          => $paged,
    'post_status'    => ['publish', 'draft'],
];
$query = new WP_Query($args);
?>

<!-- [ Main Content ] -->
<div class="pc-container">
    <div class="pc-content">
        <!-- [ Breadcrumb ] -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php esc_html_e('Food Menu', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>
        <!-- [ Main Content Section ] -->
        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card table-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="sm:flex items-center justify-between">
                            <h5 class="mb-3 sm:mb-0"><?php esc_html_e('All Food', 'palgoals-dash'); ?></h5>
                            <button data-pc-animate="side-fall" type="button" class="btn btn-primary" data-pc-toggle="modal" data-pc-target="#animateModal">
                                <?php esc_html_e('Add Food', 'palgoals-dash'); ?>
                            </button>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-hover" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"></th>
                                        <th><?php esc_html_e('Image', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Title', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Price', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Categories', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Status', 'palgoals-dash'); ?></th>
                                        <th><?php esc_html_e('Actions', 'palgoals-dash'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query->have_posts()) : ?>
                                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="page_ids[]" value="<?php echo esc_attr(get_the_ID()); ?>" class="select_single">
                                                </td>
                                                <td>
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <?php the_post_thumbnail('thumbnail', ['class' => 'w-16 h-16']); ?>
                                                    <?php else : ?>
                                                        <span><?php esc_html_e('No Image', 'palgoals-dash'); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php the_title(); ?></td>
                                                <td>
                                                    <?php 
                                                    $price = get_post_meta(get_the_ID(), '_pg_food_menu_price', true);
                                                    $currency = get_post_meta(get_the_ID(), '_pg_food_menu_currency', true);
                                                    $currency_label = [
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
                                                    ][$currency] ?? $currency;
                                                    echo esc_html($price) . ' ' . esc_html($currency_label);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $categories = get_the_terms(get_the_ID(), 'pg_food_menu_category');
                                                    if ($categories && !is_wp_error($categories)) :
                                                        foreach ($categories as $category) :
                                                            echo '<span class="category-label">' . esc_html($category->name) . '</span>';
                                                        endforeach;
                                                    else :
                                                        esc_html_e('No Categories', 'palgoals-dash');
                                                    endif;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php $current_status = get_post_status(); ?>
                                                    <a class="badge text-white  <?php echo ($current_status === 'publish') ? 'bg-green-500' : 'bg-secondary-500'; ?>" data-page-id="<?php echo esc_attr(get_the_ID()); ?>">
                                                        <?php echo ucfirst($current_status); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo esc_url(get_permalink()); ?>" target="_blank" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                        <i class="ti ti-eye text-xl leading-none"></i>
                                                    </a>
                                                    <a href="<?php echo home_url('/dashboard/pg-menus/edit-food?id=' . get_the_ID()); ?>" class="btn-link-secondary">
                                                        <i class="ti ti-edit text-xl leading-none"></i>
                                                    </a>
                                                    <a href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary delete-food-item" data-post-id="<?php echo esc_attr(get_the_ID()); ?>">
                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="text-center"><?php esc_html_e('No Food Menus Found.', 'palgoals-dash'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <?php if ($query->max_num_pages > 1) : ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <!-- الصفحة السابقة -->
                                <?php if ($paged > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>">
                                            <?php esc_html_e('Previous', 'palgoals-dash'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- أرقام الصفحات -->
                                <?php for ($i = 1; $i <= $query->max_num_pages; $i++) : ?>
                                    <li class="page-item <?php echo ($paged === $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo esc_url(get_pagenum_link($i)); ?>">
                                            <?php echo esc_html($i); ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- الصفحة التالية -->
                                <?php if ($paged < $query->max_num_pages) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>">
                                            <?php esc_html_e('Next', 'palgoals-dash'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- [ Include Add Food Modal ] -->
<?php include plugin_dir_path(dirname(__DIR__)) . 'templates/food/add-food.php'; ?>
<?php include plugin_dir_path(dirname(__DIR__, 3)) . 'templates/partials/footer.php'; ?>
