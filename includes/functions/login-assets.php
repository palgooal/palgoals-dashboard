<?php
// تحميل CSS و JavaScript لصفحة تسجيل الدخول
function palgoals_enqueue_login_dashboard_assets() {
    if (get_query_var('login') === 'true') {
        palgoals_enqueue_shared_assets();
        wp_enqueue_script('palgoals-login', plugin_dir_url(__FILE__) . '../../assets/js/pg-pages/login.js', ['jquery'], null, true);

        wp_localize_script('palgoals-login', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('custom_login_nonce'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_login_dashboard_assets');