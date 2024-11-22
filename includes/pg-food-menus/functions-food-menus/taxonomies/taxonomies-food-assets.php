<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

function palgoals_enqueue_category_dashboard_assets() {
    if (get_query_var('pg_category_menus')) {
        palgoals_enqueue_shared_assets();
        wp_enqueue_media();
        wp_enqueue_script('palgoals-category-image', plugin_dir_url(dirname(__DIR__, 2)) . 'assets/js/pg-pages/category-image.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'palgoals_enqueue_category_dashboard_assets');