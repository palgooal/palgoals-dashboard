<?php
// تحميل ملفات الـ CSS والـ JavaScript المشتركة
function palgoals_enqueue_shared_assets() {
    // تحميل ملفات CSS
    wp_enqueue_style('palgoals-inter', plugin_dir_url(__DIR__) . 'assets/fonts/inter/inter.css');
    wp_enqueue_style('palgoals-duotone-style', plugin_dir_url(__DIR__) . 'assets/fonts/phosphor/duotone/style.css');
    wp_enqueue_style('palgoals-tabler-icons.min', plugin_dir_url(__DIR__) . 'assets/fonts/tabler-icons.min.css');
    wp_enqueue_style('palgoals-feather', plugin_dir_url(__DIR__) . 'assets/fonts/feather.css');
    wp_enqueue_style('palgoals-fontawesome', plugin_dir_url(__DIR__) . 'assets/fonts/fontawesome.css');
    wp_enqueue_style('palgoals-material', plugin_dir_url(__DIR__) . 'assets/fonts/material.css');
    wp_enqueue_style('eeedashboard-style', plugin_dir_url(__DIR__) . 'assets/css/style.css');
    //wp_enqueue_style('palgoals-tailwind', plugin_dir_url(__DIR__) . 'dist/output.css');

    // تحميل ملفات JavaScript
    wp_enqueue_script('palgoals-simplebar', plugin_dir_url(__DIR__) . 'assets/js/plugins/simplebar.min.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-popper', plugin_dir_url(__DIR__) . 'assets/js/plugins/popper.min.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-custom-icon', plugin_dir_url(__DIR__) . 'assets/js/icon/custom-icon.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-feather', plugin_dir_url(__DIR__) . 'assets/js/plugins/feather.min.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-component', plugin_dir_url(__DIR__) . 'assets/js/component.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-themejs', plugin_dir_url(__DIR__) . 'assets/js/theme.js', array('jquery'), null, true);
    wp_enqueue_script('palgoals-pages-script-js', plugin_dir_url(__DIR__) . 'assets/js/script.js', array('jquery'), null, true);
}
