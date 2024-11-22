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
    ]);
}
add_action('init', 'palgoals_register_food_menu_post_type');
