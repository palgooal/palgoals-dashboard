<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

// تضمين الوظائف المساعدة
include_once plugin_dir_path(__DIR__) . '/functions/pages/pages-rewrite.php';
include_once plugin_dir_path(__DIR__) . '/functions/pages/pages-assets.php';
include_once plugin_dir_path(__DIR__) . '/functions/pages/pages-ajax.php';