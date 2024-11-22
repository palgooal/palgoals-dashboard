<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

// تضمين الوظائف المساعدة
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/food-rewrite.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/food-assets.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/post-types-food-menu.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/meta-boxes-food-menu-meta.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/ajax-food-menu-handlers.php';


include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/taxonomies/taxonomies-food-rewrite.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/taxonomies/taxonomies-food-assets.php';
include_once plugin_dir_path(__DIR__) . 'pg-food-menus/functions-food-menus/taxonomies/taxonomies-food-menu-category.php';