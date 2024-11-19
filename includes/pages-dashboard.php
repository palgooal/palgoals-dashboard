<?php
// إضافة قاعدة إعادة الكتابة لإنشاء رابط مخصص لـ dashboard/pg-pages
function palgoals_pages_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/pg-pages/?$', 'index.php?pg_pages=true', 'top');
}
add_action('init', 'palgoals_pages_dashboard_rewrite_rule');

// إضافة المتغير المخصص إلى query_vars
function palgoals_add_pages_query_vars($vars) {
    $vars[] = 'pg_pages';
    return $vars;
}
add_filter('query_vars', 'palgoals_add_pages_query_vars');

// توجيه الصفحة إلى قالب مخصص عند التحقق من المتغير
function palgoals_pages_dashboard_page() {
    if (get_query_var('pg_pages') === 'true') {
        // التحقق من تسجيل دخول المستخدم
        if (is_user_logged_in()) {
            include plugin_dir_path(__DIR__) . 'templates/pg-pages.php';
            } else {
                // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
                wp_redirect(wp_login_url());
                exit;
            }
            exit;
        }
    }
add_action('template_redirect', 'palgoals_pages_dashboard_page');

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم المخصصة
function palgoals_enqueue_pages_dashboard_assets() {
    if (get_query_var('pg_pages') === 'true') {
        wp_enqueue_script('palgoals-sadd-pages', plugin_dir_url(__DIR__) . 'assets/js/pg-pages/add-pages.js', array('jquery'), null, true);
        palgoals_enqueue_shared_assets();
        wp_enqueue_script('palgoals-delete-pages', plugin_dir_url(__DIR__) . 'assets/js/pg-pages/delete-pages.js', array('jquery'), null, true);
        //wp_enqueue_script('palgoals-status-pages', plugin_dir_url(__DIR__) . 'assets/js/pg-pages/status.js', array('jquery'), null, true);
        
        // Localize the script to pass data
        wp_localize_script('palgoals-delete-pages', 'palgoals_delete_pages_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('palgoals_delete_page_nonce')
        ));
        wp_localize_script('palgoals-sadd-pages', 'palgoals_sadd_pages_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('palgoals_nonce')
        ));
        
        
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_pages_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_remove_theme_styles() {
    if (get_query_var('pg_pages') === 'true') {
        global $wp_styles;
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_remove_theme_styles', 99);

// تنفيذ حذف الصفحة بواسطة AJAX
function palgoals_delete_page() {
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'palgoals_delete_page_nonce')) {
        wp_send_json_error('1تحقق nonce فشل.');
        return;
    }

    if (isset($_POST['page_id']) && is_numeric($_POST['page_id'])) {
        $page_id = intval($_POST['page_id']);
        if (current_user_can('delete_pages', $page_id)) {
            $deleted = wp_delete_post($page_id, true);
            if ($deleted) {
                wp_send_json_success();
            } else {
                wp_send_json_error('1فشل في حذف الصفحة.');
            }
        } else {
            wp_send_json_error('1ليس لد��ك صلاحية لحذف هذه الصفحة.');
        }
    } else {
        wp_send_json_error('1معرف الصفحة غير صالح.');
    }
}
add_action('wp_ajax_palgoals_delete_page', 'palgoals_delete_page');

add_action('wp_ajax_toggle_page_status', 'toggle_page_status');

function toggle_page_status() {
    if (!isset($_POST['page_id']) || !isset($_POST['new_status'])) {
        wp_send_json_error(__('Invalid request', 'palgoals-dash'));
    }

    $page_id = absint($_POST['page_id']);
    $new_status = sanitize_text_field($_POST['new_status']); // الحصول على الحالة الجديدة

    // إضافة سجل للأخطاء
    error_log('Page ID: ' . $page_id . ' New Status: ' . $new_status);

    // تحديث الحالة إلى القيمة الجديدة
    $updated = wp_update_post(array(
        'ID' => $page_id,
        'post_status' => $new_status
    ));
}



function palgoals_create_page() {
    // التحقق من nonce للأمان
    check_ajax_referer('palgoals_nonce', 'security');

    if (isset($_POST['title']) && isset($_POST['slug'])) {
        // إعداد بيانات الصفحة الجديدة
        $new_page = array(
            'post_title'   => sanitize_text_field($_POST['title']),
            'post_name'    => sanitize_title($_POST['slug']),
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        // إنشاء الصفحة
        $page_id = wp_insert_post($new_page);

        if ($page_id && !is_wp_error($page_id)) {
            // التحقق من إمكانية استخدام Elementor
            if (class_exists('Elementor\Plugin')) {
                // جلب رابط التحرير في Elementor
                $edit_link = Elementor\Plugin::instance()->documents->get($page_id)->get_edit_url();

                // التحقق مما إذا كان رابط التحرير في Elementor صالح
                if (!empty($edit_link)) {
                    wp_send_json_success(array('edit_link' => $edit_link));
                } else {
                    wp_send_json_error(array('message' => 'لا يمكن فتح الرابط في Elementor.'));
                }
            } else {
                // إذا لم يكن Elementor مفعل، جلب رابط التحرير في المحرر العادي
                $edit_link = get_edit_post_link($page_id);
                wp_send_json_success(array('edit_link' => $edit_link));
            }
        } else {
            wp_send_json_error(array('message' => 'فشل في إنشاء الصفحة.'));
        }
    } else {
        wp_send_json_error(array('message' => 'العنوان و Slug مطلوبين.'));
    }
}
add_action('wp_ajax_create_page', 'palgoals_create_page');

// إضافة توجيه مباشر إلى المحرر في Elementor
add_action('wp_ajax_nopriv_create_page', function() {
    // إذا كان المستخدم غير مسجل الدخول
    wp_send_json_error(array('message' => 'يجب تسجيل الدخول.'));
});

add_action('admin_post_create_page', function() {
    // تحقق من وجود nonce
    if (!isset($_POST['palgoals_nonce']) || !wp_verify_nonce($_POST['palgoals_nonce'], 'palgoals_nonce')) {
        wp_die('تحقق غير صالح');
    }

    // استخدم الدالة الحالية لإنشاء الصفحة
    palgoals_create_page();
});



// إضافة الأكشن لـ Ajax في لوحة التحكم وفي الواجهة العامة
add_action('wp_ajax_palgoals_create_new_page', 'palgoals_create_new_page');
add_action('wp_ajax_nopriv_palgoals_create_new_page', 'palgoals_create_new_page');

function palgoals_create_new_page() {
    // تحقق من وجود عنوان الصفحة و slug
    if (!isset($_POST['title']) || !isset($_POST['slug'])) {
        wp_send_json_error(array('message' => __('Page title or slug is missing.', 'palgoals-core')));
    }

    // تنظيف المدخلات
    $page_title = sanitize_text_field($_POST['title']);
    $page_slug = sanitize_title($_POST['slug']);

    // تحقق إذا كانت الصفحة موجودة بالفعل
    if (get_page_by_path($page_slug)) {
        wp_send_json_error(array('message' => __('Page with this slug already exists.', 'palgoals-core')));
    }

    // إنشاء الصفحة
    $page_id = wp_insert_post(array(
        'post_title'   => $page_title,
        'post_name'    => $page_slug,
        'post_type'    => 'page',
        'post_status'  => 'publish',
    ));

    if (is_wp_error($page_id)) {
        wp_send_json_error(array('message' => $page_id->get_error_message()));
    }

    // الحصول على رابط محرر Elementor للصفحة الجديدة
    $elementor_url = admin_url('post.php?post=' . $page_id . '&action=elementor');

    // إرسال النجاح مع الرابط إلى Elementor
    wp_send_json_success(array('elementor_url' => $elementor_url));
}
function palgoals_add_ajaxurl_to_head() {
    echo '<script type="text/javascript">
        var ajaxurl = "' . admin_url('admin-ajax.php') . '";
    </script>';
}
add_action('wp_head', 'palgoals_add_ajaxurl_to_head');



?>
