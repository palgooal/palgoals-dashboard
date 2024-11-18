<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

// تضمين الوظائف المساعدة
include_once plugin_dir_path(__DIR__) . '/functions/login-rewrite.php';
include_once plugin_dir_path(__DIR__) . '/functions/login-redirect.php';
include_once plugin_dir_path(__DIR__) . '/functions/login-assets.php';
include_once plugin_dir_path(__DIR__) . '/functions/login-ajax.php';



