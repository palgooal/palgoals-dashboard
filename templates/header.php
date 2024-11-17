<!doctype html>
<html lang="<?php echo get_locale(); ?>" dir="<?php echo ( is_rtl() ) ? 'rtl' : 'ltr'; ?>" class="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="<?php echo ( is_rtl() ) ? 'rtl' : 'ltr'; ?>" data-pc-theme_contrast="" data-pc-theme="light">
<!-- [Head] start -->

<head>
  <title><?php wp_title(); ?></title>
  <!-- [Meta] -->
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta
    name="description"
    content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies."
  />
  <meta
    name="keywords"
    content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
  />
  <meta name="author" content="Phoenixcoded" />
<!-- [Favicon] icon -->
<link rel="icon" href="<?php echo plugin_dir_url(__DIR__). 'assets/images/favicon.svg';?>" type="image/x-icon" />
<!-- [Template CSS Files] -->
<?php wp_head(); ?>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body <?php body_class(); ?>>
<?php include plugin_dir_path(__DIR__). 'templates/partials/sidebar-menu.php';?>
<?php

?>

