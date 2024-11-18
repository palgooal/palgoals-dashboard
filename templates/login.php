<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// إعادة التوجيه إذا كان المستخدم مسجل الدخول
if (is_user_logged_in()) {
    wp_safe_redirect(site_url('/dashboard/'));
    exit;
}

wp_head();
?>

<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
    <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
        <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[loader-animate]"></div>
    </div>
</div>

<div class="auth-main relative">
    <div class="auth-wrapper v2 flex items-center w-full h-full min-h-screen">
        
        <!-- جانب الصورة -->
        <div class="auth-sidecontent">
            <img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/authentication/img-auth-sideimg.jpg'); ?>" alt="images" class="img-fluid h-screen hidden lg:block" />
        </div>
        
        <!-- نموذج تسجيل الدخول -->
        <div class="auth-form flex items-center justify-center grow flex-col min-h-screen bg-cover relative p-6 bg-theme-cardbg dark:bg-themedark-cardbg">
            <div class="card sm:my-12 w-full max-w-[480px] border-none shadow-none">
                <div class="card-body sm:!p-10">
                    
                    <!-- الشعار -->
                    <div class="text-center">
                        <a href="#"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/palgoalsnew.webp'); ?>" alt="logo" class="mx-auto" /></a>
                    </div>
                    
                    <!-- خط فاصل -->
                    <div class="relative my-5">
                        <div aria-hidden="true" class="absolute flex inset-0 items-center">
                            <div class="w-full border-t border-theme-border dark:border-themedark-border"></div>
                        </div>
                    </div>
                    
                    <!-- عنوان تسجيل الدخول -->
                    <h4 class="text-center font-medium mb-4"><?php _e('Login with your email', 'palgoals-dash'); ?></h4>
                    
                    <!-- نموذج تسجيل الدخول -->
                    <form id="login-form">
                        <!-- Nonce للحماية -->
                        <input type="hidden" id="login_nonce" value="<?php echo wp_create_nonce('custom_login_nonce'); ?>">
                        
                        <!-- حقل البريد الإلكتروني -->
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" placeholder="<?php _e('Email Address', 'palgoals-dash'); ?>" required />
                        </div>
                        
                        <!-- حقل كلمة المرور -->
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" placeholder="<?php _e('Password', 'palgoals-dash'); ?>" required />
                        </div>
                        
                        <!-- زر تسجيل الدخول -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-full"><?php _e('Login', 'palgoals-dash'); ?></button>
                        </div>
                        
                        <!-- رسالة الخطأ -->
                        <div id="login-error" class="hidden text-red-500 mt-3"></div>
                        
                        <!-- رسالة النجاح -->
                        <div id="login-success" class="hidden text-green-500 mt-3"></div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php wp_footer(); ?>
