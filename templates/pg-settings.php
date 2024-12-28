<?php
// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// الحصول على إعدادات الموقع
$site_title = get_bloginfo('name'); // عنوان الموقع
$tagline = get_bloginfo('description'); // وصف الموقع
$site_icon = get_site_icon_url(); // أيقونة الموقع
$site_language = get_option('WPLANG'); // لغة الموقع

// عرض قائمة اللغات المثبتة
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
        <!-- [ عنوان الصفحة ] -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php _e('Edit Settings', 'palgoals-dash'); ?></h2>
                </div>
            </div>
        </div>

        <!-- [ محتوى الصفحة الرئيسي ] -->
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
                                <!-- عنوان الموقع -->
                                <div class="col-span-12 md:col-span-6">
                                    <label for="site_title" class="form-label"><?php _e('Site Title', 'palgoals-dash'); ?></label>
                                    <input type="text" id="site_title" name="site_title" class="form-control" value="<?php echo esc_attr($site_title); ?>" />
                                </div>

                                <!-- الوصف -->
                                <div class="col-span-12 md:col-span-6">
                                    <label for="tagline" class="form-label"><?php _e('Tagline', 'palgoals-dash'); ?></label>
                                    <input type="text" id="tagline" name="tagline" class="form-control" value="<?php echo esc_attr($tagline); ?>" />
                                </div>

                                <!-- أيقونة الموقع -->
                                <div class="col-span-12 md:col-span-6">
                                    <label for="site_icon" class="form-label"><?php _e('Site Icon', 'palgoals-dash'); ?></label>
                                    <?php if ($site_icon): ?>
                                        <img src="<?php echo esc_url($site_icon); ?>" alt="Site Icon" style="width: 50px; height: 50px;" id="site_icon_preview">
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary" id="upload-site-icon"><?php _e('Upload Site Icon', 'palgoals-dash'); ?></button>
                                    <input type="hidden" id="site_icon_url" name="site_icon_url" value="<?php echo esc_url($site_icon); ?>" />
                                </div>

                                <!-- شعار الموقع -->
                                <div class="col-span-12 md:col-span-6">
                                    <label for="custom_logo" class="form-label"><?php _e('Update Site Logo', 'palgoals-dash'); ?></label>
                                    <?php if (has_custom_logo()) : ?>
                                        <?php 
                                        $custom_logo_id = get_theme_mod('custom_logo'); 
                                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                                        ?>
                                        <img src="<?php echo esc_url($logo_url); ?>" alt="Site Logo" style="width: 50px; height: 50px;" id="custom_logo_preview">
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary" id="upload-custom-logo"><?php _e('Upload New Logo', 'palgoals-dash'); ?></button>
                                    <input type="hidden" id="custom_logo_id" name="custom_logo_id" value="<?php echo esc_attr($custom_logo_id); ?>" />
                                </div>

                                <!-- زر الحفظ -->
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
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // رفع أيقونة الموقع
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

    // رفع شعار الموقع
    $('#upload-custom-logo').on('click', function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: '<?php _e('Select Site Logo', 'palgoals-dash'); ?>',
            button: {
                text: '<?php _e('Use this logo', 'palgoals-dash'); ?>'
            },
            multiple: false
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#custom_logo_preview').attr('src', attachment.url);
            $('#custom_logo_id').val(attachment.id);

            // تحديث شعار الهوية عبر AJAX
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'update_site_identity_logo',
                    attachment_id: attachment.id,
                    security: '<?php echo wp_create_nonce('site_logo_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('<?php _e('Logo updated successfully!', 'palgoals-dash'); ?>');
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        })
        .open();
    });
});
</script>

<?php include plugin_dir_path(__DIR__) . 'templates/partials/footer.php'; ?>