<?php
function palgoals_register_food_menu_post_type() {
    register_post_type('pg_food_menu', [
        'labels' => [
            'name' => __('Food Menus', 'palgoals-dash'),
            'singular_name' => __('Food Menu', 'palgoals-dash'),
            'add_new_item' => __('Add New Food Menu', 'palgoals-dash'),
            'edit_item' => __('Edit Food Menu', 'palgoals-dash'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-carrot',
        'supports' => ['title', 'editor', 'thumbnail'],
        'capability_type' => 'post', // استخدام صلاحيات المنشورات العادية
        'capabilities' => array(
            'edit_post' => 'edit_pg_food_menu',
            'read_post' => 'read_pg_food_menu',
            'delete_post' => 'delete_pg_food_menu',
            'edit_posts' => 'edit_pg_food_menus',
            'edit_others_posts' => 'edit_others_pg_food_menus',
            'publish_posts' => 'publish_pg_food_menus',
            'read_private_posts' => 'read_private_pg_food_menus',
        ),
        'map_meta_cap' => true, // تفعيل الخرائط للصلاحيات
    ]);
}
add_action('init', 'palgoals_register_food_menu_post_type');
