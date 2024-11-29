<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Register the Food Menu Custom Post Type
 */
function palgoals_register_food_menu_post_type() {
    $labels = [
        'name'               => __('Food Menus', 'palgoals-dash'),
        'singular_name'      => __('Food Menu', 'palgoals-dash'),
        'add_new_item'       => __('Add New Food Menu', 'palgoals-dash'),
        'edit_item'          => __('Edit Food Menu', 'palgoals-dash'),
    ];

    $capabilities = [
        'edit_post'           => 'edit_pg_food_menu',
        'read_post'           => 'read_pg_food_menu',
        'delete_post'         => 'delete_pg_food_menu',
        'edit_posts'          => 'edit_pg_food_menus',
        'edit_others_posts'   => 'edit_others_pg_food_menus',
        'publish_posts'       => 'publish_pg_food_menus',
        'read_private_posts'  => 'read_private_pg_food_menus',
    ];

    $args = [
        'labels'            => $labels,
        'public'            => true,
        'menu_icon'         => 'dashicons-carrot',
        'supports'          => ['title', 'editor', 'thumbnail'],
        'capability_type'   => 'post',
        'capabilities'      => $capabilities,
        'map_meta_cap'      => true, // Enable automatic mapping of meta capabilities
        'has_archive'       => true, // تمكين الأرشيف
        'rewrite'           => [
            'slug'       => 'food-menus', // الرابط الثابت
            'with_front' => false,       // لا تستخدم الهيكل الأساسي
        ],
        
    ];

    register_post_type('pg_food_menu', $args);
}

add_action('init', 'palgoals_register_food_menu_post_type');