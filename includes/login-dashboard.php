<?php
function palgoals_login_dashboard_rewrite_rule() {
    add_rewrite_rule('^login/?$', 'index.php?login=true', 'top');
}
add_action('init', 'palgoals_login_dashboard_rewrite_rule');

function palgoals_login_query_vars($vars) {
    $vars[] = 'login';
    return $vars;
}
add_filter('query_vars', 'palgoals_login_query_vars');

// توجيه الصفحة إلى قالب مخصص عند التحقق من المتغير
function palgoals_login_page() {
    if (get_query_var('login') === 'true') {
        include plugin_dir_path(__DIR__) . 'templates/login.php';
        exit;
    }
}
add_action('template_redirect', 'palgoals_login_page');

// تحميل ملفات الـ CSS والـ JavaScript للوحة التحكم المخصصة
function palgoals_enqueue_login_dashboard_assets() {
    if (get_query_var('login') === 'true') {
        palgoals_enqueue_shared_assets();
        wp_enqueue_script('palgoals-login', plugin_dir_url(__DIR__) . 'assets/js/pg-pages/login.js', array('jquery'), null, true);
        // تمرير رابط admin-ajax.php إلى JavaScript
        wp_localize_script('palgoals-login', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_login_dashboard_assets');

// إعادة التوجيه من صفحة تسجيل الدخول الافتراضية إلى الصفحة المخصصة
function redirect_to_login() {
    // الرابط المخصص لصفحة تسجيل الدخول
    $login_page = home_url('/login/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);
    // تحقق إذا كان المستخدم لم يقم بتسجيل الدخول وهو يحاول الوصول إلى wp-login.php أو wp-admin
    if (($page_viewed == "wp-login.php" || $page_viewed == "wp-admin") && !is_user_logged_in()) {
        // استثناء: السماح بعمليات POST لتسجيل الدخول دون إعادة التوجيه
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return;
        }
        // إعادة التوجيه إلى صفحة تسجيل الدخول المخصصة
        wp_redirect($login_page);
        exit;
    }
}
add_action('init', 'redirect_to_login');

function restrict_wp_admin_access() {
    // تحقق من أن المستخدم مسجل الدخول ويقوم بمحاولة الوصول إلى wp-admin
    if (is_admin() && !defined('DOING_AJAX')) {
        $current_user = wp_get_current_user();

        // السماح للمستخدم 'support' أو لمن لديهم صلاحيات كاملة
        if ($current_user->user_login === 'support' || current_user_can('manage_options')) {
            return;
        }

        // السماح للمستخدمين بالوصول إلى رابط تحرير Elementor
        if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
            return;
        }

        // إعادة توجيه المستخدمين الآخرين إلى /dashboard/ أو الصفحة الرئيسية
        wp_redirect(site_url('/dashboard/'));
        exit;
    }
}
add_action('admin_init', 'restrict_wp_admin_access');


function custom_login_action() {
    // تحقق من وجود بيانات POST
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        wp_send_json_error(['message' => 'Email and password are required']);
        return;
    }

    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    $user = wp_authenticate($email, $password);

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Invalid email or password']);
    } else {
        // تسجيل دخول المستخدم
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        // تحدي�� رابط إعادة التوجيه بعد تسجيل الدخول
        $redirect_url = $user->user_login === 'support' ? admin_url() : site_url('/dashboard/');
        wp_send_json_success(['redirect_url' => $redirect_url]);
    }
}

add_action('wp_ajax_custom_login_action', 'custom_login_action');
add_action('wp_ajax_nopriv_custom_login_action', 'custom_login_action');