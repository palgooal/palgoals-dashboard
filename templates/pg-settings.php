<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// الحصول على إعدادات الموقع
$site_title = get_bloginfo('name'); // Site Title
$tagline = get_bloginfo('description'); // Tagline
$site_icon = get_site_icon_url(); // Site Icon
$site_language = get_option('WPLANG'); // Site Language

// الحصول على اللغات المثبتة
$installed_languages = get_available_languages();
$languages = get_available_languages();
// عرض قائمة اللغات المتاحة
wp_dropdown_languages(array(
    'id'       => 'site_language',
    'name'     => 'site_language',
    'selected' => get_option('WPLANG'),
));

// تضمين الهيدر
include plugin_dir_path(__DIR__) . 'templates/partials/header.php';

?>

<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php _e('Edit Settings', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-12 gap-x-6">
            <div class="col-span-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php _e('Edit Settings', 'palgoals-dash'); ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="pg-settings-form" method="post" enctype="multipart/form-data">
                            <?php wp_nonce_field('settings_ajax_nonce', 'security'); ?>
                            <div class="grid grid-cols-12 gap-x-6">
                                <!-- Site Title -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="site_title" class="form-label"><?php _e('Site Title', 'palgoals-dash'); ?></label>
                                        <input type="text" id="site_title" name="site_title" class="form-control" value="<?php echo esc_attr($site_title); ?>" />
                                    </div>
                                </div>

                                <!-- Tagline -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="tagline" class="form-label"><?php _e('Tagline', 'palgoals-dash'); ?></label>
                                        <input type="text" id="tagline" name="tagline" class="form-control" value="<?php echo esc_attr($tagline); ?>" />
                                    </div>
                                </div>

                                <!-- Site Icon -->
                                <div class="col-span-12 md:col-span-6">
                                    <div class="mb-3">
                                        <label for="site_icon" class="form-label"><?php _e('Site Icon', 'palgoals-dash'); ?></label>
                                        <?php if ($site_icon): ?>
                                            <img src="<?php echo esc_url($site_icon); ?>" alt="Site Icon" style="width: 50px; height: 50px;" id="site_icon_preview">
                                        <?php endif; ?>
                                        <button type="button" class="button btn btn-secondary" id="upload-site-icon"><?php _e('Upload Site Icon', 'palgoals-dash'); ?></button>
                                        <input type="hidden" id="site_icon_url" name="site_icon_url" value="<?php echo esc_url($site_icon); ?>" />
                                    </div>
                                </div>

                                <!-- Site Language -->
                                <div class="col-span-12 md:col-span-6">
    <div class="mb-3">
        <label for="site_language" class="form-label"><?php _e('Site Language', 'palgoals-dash'); ?></label>
        <select id="site_language" name="site_language" class="form-control">
            <option value="ar" <?php selected(get_option('WPLANG'), 'ar'); ?>><?php _e('Arabic', 'palgoals-dash'); ?></option>
            <option value="en_US" <?php selected(get_option('WPLANG'), 'en_US'); ?>><?php _e('English (United States)', 'palgoals-dash'); ?></option>
        </select>
    </div>
</div>




                                <!-- Submit Button -->
                                <div class="col-span-12 text-right">
                                    <button type="button" id="save-settings" class="btn btn-primary"><?php _e('Save Settings', 'palgoals-dash'); ?></button>
                                </div>
                            </div>
                        </form>
                        <div id="pg-settings-message" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#upload-site-icon').on('click', function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: '<?php _e('Select Site Icon', 'palgoals-dash'); ?>',
            button: {
                text: '<?php _e('Use this icon', 'palgoals-dash'); ?>'
            },
            multiple: false
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#site_icon_preview').attr('src', attachment.url);
            $('#site_icon_url').val(attachment.url);
        })
        .open();
    });
});
</script>

<?php include plugin_dir_path(__DIR__) . 'templates/partials/footer.php'; ?>