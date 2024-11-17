<?php
function palgoals_media_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-media/?$', 'index.php?pg_media=true', 'top');
}
add_action('init', 'palgoals_media_dashboard_rewrite_rule');

function palgoals_media_query_vars($vars) {
    $vars[] = 'pg_media';
    return $vars;
}
add_filter('query_vars', 'palgoals_media_query_vars');

function palgoals_media_dashboard_page() {
    if (get_query_var('pg_media') === 'true') {
        if (is_user_logged_in()) {
            include plugin_dir_path(__DIR__) . 'templates/media/pg-media.php';
        } else {
            wp_redirect(wp_login_url());
            exit;
        }
        exit;
    }
}
add_action('template_redirect', 'palgoals_media_dashboard_page');

function palgoals_enqueue_media_dashboard_assets() {
    if (get_query_var('pg_media') === 'true') {
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
        wp_enqueue_script(
            'palgoals-media',
            plugin_dir_url(__DIR__) . 'assets/js/pg-pages/media.js',
            array('jquery'),
            null,
            true
        );
        wp_localize_script('palgoals-media', 'ajax_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('media_upload_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_media_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_remove_theme_styles1() {
    if (get_query_var('pg_media') === 'true') {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_styles1', 99);

function palgoals_handle_media_upload() {
    if (!empty($_POST['media_ids'])) {
        $media_ids      = $_POST['media_ids'];
        $uploaded_files = array();

        foreach ($media_ids as $media_id) {
            $media_url = wp_get_attachment_url($media_id);
            if ($media_url) {
                $uploaded_files[] = $media_url;
            }
        }

        echo json_encode(array('success' => 'Files uploaded successfully.', 'files' => $uploaded_files));
    } else {
        echo json_encode(array('error' => 'No files uploaded.'));
    }
    wp_die();
}
add_action('wp_ajax_palgoals_handle_media_upload', 'palgoals_handle_media_upload');

function palgoals_handle_media_delete() {
    if (!empty($_POST['media_id']) && check_ajax_referer('media_upload_nonce', 'nonce', false)) {
        $media_id = intval($_POST['media_id']);
        
        if (wp_delete_attachment($media_id, true)) {
            echo json_encode(array('success' => 'File deleted successfully.'));
        } else {
            echo json_encode(array('error' => 'Failed to delete file.'));
        }
    } else {
        echo json_encode(array('error' => 'Invalid request.'));
    }
    wp_die();
}
add_action('wp_ajax_palgoals_handle_media_delete', 'palgoals_handle_media_delete');


function palgoals_search_media() {
    // تحقق من nonce
    if (!check_ajax_referer('media_upload_nonce', 'nonce', false)) {
        echo json_encode(array('error' => 'Invalid nonce.'));
        wp_die();
    }

    $search_term = sanitize_text_field($_POST['search_term']);
    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -1, // استخدم -1 لتحميل جميع العناصر
        's'              => $search_term,
    );

    $media_query = new WP_Query($args);
    $total_items = $media_query->found_posts; // احصل على العدد الإجمالي للوسائط
    ob_start();

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
                                           class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary edit-media-button" data-id="<?php echo get_the_ID(); ?>">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                    </div>
                                    <div class="shrink-0">
                                        <a class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary delete-media-button" data-id="<?php echo get_the_ID(); ?>">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
    else :
        ?>
        <div class="col-span-12">
            <p class="text-center"><?php _e('No media found.', 'palgoals-dash'); ?></p>
        </div>
        <?php
    endif;
    
    wp_reset_postdata();

    $html = ob_get_clean();
    
    // إرجاع استجابة JSON مع العدد الإجمالي
    echo json_encode(array('success' => true, 'html' => $html, 'total_items' => $total_items));
    wp_die();
}
add_action('wp_ajax_palgoals_search_media', 'palgoals_search_media');

function palgoals_get_media_count() {
    if (!check_ajax_referer('media_upload_nonce', 'nonce', false)) {
        echo json_encode(array('error' => 'Invalid nonce.'));
        wp_die();
    }

    // إعداد استعلام للوسائط
    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -1, // جلب جميع الوسائط
    );

    $media_query = new WP_Query($args);
    $total_items = $media_query->found_posts; // اجلب العدد الإجمالي للصور

    echo json_encode(array('success' => true, 'total_items' => $total_items));
    wp_die();
}
add_action('wp_ajax_palgoals_get_media_count', 'palgoals_get_media_count');

