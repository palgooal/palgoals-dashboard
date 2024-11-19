<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تضمين الهيدر
include plugin_dir_path(__DIR__) . '/partials/header.php';
?>

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php _e('Media', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h5 class="mb-0"><?php _e('All Media', 'palgoals-dash'); ?></h5>
                            <div class="flex items-center gap-4">
                                <input type="text" id="media-search-input" placeholder="<?php _e('Search Media...', 'palgoals-dash'); ?>" class="form-control">
                                <button id="open-media-button" class="btn btn-primary">
                                    <?php _e('Add Media', 'palgoals-dash'); ?>
                                </button>
                            </div>
                        </div>
                        
                    </div>

                    <div class="card-body">
    <div id="message-container"></div>
    <div class="grid grid-cols-12 gap-x-6" id="media-library">
        <?php
        // تعيين القيمة الافتراضية لـ $offset إذا لم تكن موجودة
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        $media_query = new WP_Query(array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 12, // عرض 12 عنصرًا في كل مرة
            'offset'         => $offset,
        ));

        if ($media_query->have_posts()) :
            while ($media_query->have_posts()) : $media_query->the_post();
                $media_url = wp_get_attachment_url(get_the_ID());
                $media_title = get_the_title();
        ?>
                <div class="col-span-12 sm:col-span-6 lg:col-span-2 2xl:col-span-3" id="media-<?php echo get_the_ID(); ?>">
                    <div class="">
                        <div class="p-2 h-48">
                            <div class="relative">
                                <img src="<?php echo esc_url($media_url); ?>" 
                                     alt="<?php echo esc_attr($media_title); ?>" 
                                     class="w-full h-[150px] object-cover" />
                            </div>
                            <ul class="py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                <li class="list-group-item">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <a href="#" 
                                               class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary edit-media-button" 
                                               data-id="<?php echo get_the_ID(); ?>">
                                                <i class="ti ti-edit text-xl leading-none"></i>
                                            </a>
                                        </div>
                                        <div class="shrink-0">
                                            <a href="#" 
                                               class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary delete-media-button" 
                                               data-id="<?php echo get_the_ID(); ?>">
                                                <i class="ti ti-trash text-xl leading-none"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        <?php endwhile; else : ?>
            <div class="col-span-12">
                <p class="text-center"><?php _e('No media found.', 'palgoals-dash'); ?></p>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </div>
    <div class="text-center my-4">
        <button id="load-more-button" class="btn btn-secondary" data-offset="<?php echo $offset + 12; ?>">
            <?php _e('Load More', 'palgoals-dash'); ?>
        </button>
        <div id="media-count" class="text-center my-2">
            <p><?php printf(__('Showing %d of %d media items', 'palgoals-dash'), $offset + count($media_query->posts), $media_query->found_posts); ?></p>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php include plugin_dir_path(__DIR__). '/partials/footer.php';?>

<style>
a.edit-attachment {
    display: none !important;
}
</style>