<?php
// إضافة قاعدة إعادة الكتابة لإنشاء رابط مخصص لـ dashboard
function palgoals_dashboard_rewrite_rule() {
    add_rewrite_rule('^dashboard/?$', 'index.php?dashboard=true', 'top');
}
add_action('init', 'palgoals_dashboard_rewrite_rule');

// إضافة المتغير المخصص إلى query_vars
function palgoals_add_query_vars($vars) {
    $vars[] = 'dashboard';
    return $vars;
}
add_filter('query_vars', 'palgoals_add_query_vars');

// فحص المتغير وتوجيه الصفحة
function palgoals_custom_dashboard_page() {
    if (get_query_var('dashboard') === 'true') {
        // التحقق من تسجيل دخول المستخدم
        if(is_user_logged_in()){
            // تحميل صفحة لوحة التحكم المخصصة
            include plugin_dir_path(__DIR__) . 'templates/dashboard.php';
            } else {
                // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
                wp_redirect(wp_login_url());
                exit;
            }
            exit; // التأكد من إيقاف باقي تنفيذ الصفحات
        }
    }
add_action('template_redirect', 'palgoals_custom_dashboard_page');

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم
function palgoals_enqueue_dashboard_assets() {
    if (get_query_var('dashboard') === 'true') {
        palgoals_enqueue_shared_assets();
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_dashboard_assets');

// تعطيل ملفات CSS الخاصة بالثيم في صفحة لوحة التحكم المخصصة
function palgoals_dashboard_remove_theme_styles() {
    if (get_query_var('dashboard') === 'true') { // تحقق إذا كانت الصفحة هي لوحة التحكم المخصصة
        global $wp_styles;
        
        // تعطيل جميع ملفات CSS التي يضيفها الثيم
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, get_template_directory_uri()) === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'palgoals_dashboard_remove_theme_styles', 99);








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


