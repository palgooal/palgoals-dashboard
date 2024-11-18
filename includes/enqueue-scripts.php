<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تحميل ملفات الـ CSS والـ JavaScript المشتركة
function palgoals_enqueue_shared_assets() {
    $plugin_url = plugins_url('assets/', __DIR__);
    
    // تحميل ملفات CSS
    wp_enqueue_style('palgoals-inter', $plugin_url . 'fonts/inter/inter.css', [], null);
    wp_enqueue_style('palgoals-duotone-style', $plugin_url . 'fonts/phosphor/duotone/style.css', [], null);
    wp_enqueue_style('palgoals-tabler-icons', $plugin_url . 'fonts/tabler-icons.min.css', [], null);
    wp_enqueue_style('palgoals-feather', $plugin_url . 'fonts/feather.css', [], null);
    wp_enqueue_style('palgoals-fontawesome', $plugin_url . 'fonts/fontawesome.css', [], null);
    wp_enqueue_style('palgoals-material', $plugin_url . 'fonts/material.css', [], null);
    wp_enqueue_style('palgoals-dashboard-style', $plugin_url . 'css/style.css', [], null);
    //wp_enqueue_style('palgoals-tailwind', $plugin_url . 'dist/output.css', [], null);

    // تحميل ملفات JavaScript
    wp_enqueue_script('palgoals-simplebar', $plugin_url . 'js/plugins/simplebar.min.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-popper', $plugin_url . 'js/plugins/popper.min.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-custom-icon', $plugin_url . 'js/icon/custom-icon.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-feather', $plugin_url . 'js/plugins/feather.min.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-component', $plugin_url . 'js/component.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-themejs', $plugin_url . 'js/theme.js', ['jquery'], null, true);
    wp_enqueue_script('palgoals-script', $plugin_url . 'js/script.js', ['jquery'], null, true);
}