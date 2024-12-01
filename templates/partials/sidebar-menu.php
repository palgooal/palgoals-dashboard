<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Fetch current user information
$current_user = wp_get_current_user();
$user_name = $current_user->display_name;
$user_email = $current_user->user_email;
$avatar_url = get_avatar_url($current_user->ID, ['size' => 45]);
?>

  <!-- [ Pre-loader ] start -->
<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
  <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
    <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 transition-[transform_0.2s_linear] origin-left animate-[2.1s_cubic-bezier(0.65,0.815,0.735,0.395)_0s_infinite_normal_none_running_loader-animate]"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
  <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header flex items-center py-4 px-6 h-header-height">
      <a href="<?php echo get_home_url().'/dashboard'; ?>" class="b-brand flex items-center gap-3">
        <img src="<?php echo plugin_dir_url(__FILE__) . '../../assets/images/palgoalsnew.webp'; ?>" class="img-fluid logo-lg w-[150px]" alt="logo"  />
        <span class="badge bg-success-500/10 text-success-500 rounded-full theme-version">v1.0.0</span>
      </a>
    </div>
    <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
      <div class="card pc-user-card mx-[15px] mb-[15px] bg-theme-sidebaruserbg dark:bg-themedark-sidebaruserbg">
        <div class="card-body !p-5">
          <div class="flex items-center">
            <img class="shrink-0 w-[45px] h-[45px] rounded-full" src="<?php echo esc_url($avatar_url); ?>" alt="user-image" />
            <div class="ml-4 mr-2 grow">
              <h6 class="mb-0"><?php echo esc_html($user_name);?></h6>
              <?php
              if (!empty($current_user->roles)) {
                // جلب الدور الأول (في حال وجود أكثر من دور)
                $user_role = $current_user->roles[0];
              ?>
              <small><?php echo esc_html($user_role);?></small>
              <?php
              } else {
                echo 'No role assigned to the user.';
              }
              ?>
           </div>

          </div>

        </div>
      </div>
      <ul class="pc-navbar">
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-status-up"></use>
              </svg>
            </span>
            <span class="pc-mtext"><?php _e('Home', 'palgoals-dash'); ?></span>
            <span class="pc-arrow"><i data-feather="chevron-down"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="<?php echo get_home_url().'/dashboard'; ?>"><?php _e('Home Dashboard', 'palgoals-dash'); ?></a></li>
            <li class="pc-item"><a class="pc-link" href="<?php echo get_home_url(); ?>"><?php _e('Show website', 'palgoals-dash'); ?></a></li>
          </ul>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-layer"></use>
              </svg>
            </span>
            <span class="pc-mtext"><?php _e('Design', 'palgoals-dash'); ?></span>
            <span class="pc-arrow"><i data-feather="chevron-down"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="<?php echo get_home_url().'/dashboard/pg-pages'; ?>"><?php _e('Pages', 'palgoals-dash'); ?></a></li>
          </ul>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-layer"></use>
              </svg>
            </span>
            <span class="pc-mtext"><?php _e('Food menu', 'palgoals-dash'); ?></span>
            <span class="pc-arrow"><i data-feather="chevron-down"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="<?php echo get_home_url().'/dashboard/pg-menus'; ?>"><?php _e('Food menu', 'palgoals-dash'); ?></a></li>
            <li class="pc-item"><a class="pc-link" href="<?php echo get_home_url().'/dashboard/pg-category-menus'; ?>"><?php _e('Food Categories', 'palgoals-dash'); ?></a></li>
          </ul>
        </li>
        <li class="pc-item">
            <a href="<?php echo get_home_url().'/dashboard/pg-media'; ?>" class="pc-link">
              <span class="pc-micon">
                <svg class="pc-icon">
                  <use xlink:href="#custom-story"></use>
                </svg>
              </span>
            <span class="pc-mtext"><?php _e('Media', 'palgoals-dash'); ?></span>
            </a>
          </li>
      </ul>
    </div>
  </div>
</nav>
  <!-- [ Sidebar Menu ] end -->
  <!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow"><!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
      <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup lg:hidden">
      <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
        <i class="ti ti-menu-2 text-2xl leading-none"></i>
      </a>
    </li>
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
    <li class="dropdown pc-h-item">
      <a
        class="pc-head-link dropdown-toggle me-0"
        data-pc-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <svg class="pc-icon">
          <use xlink:href="#custom-sun-1"></use>
        </svg>
      </a>
      <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
        <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
          <svg class="pc-icon w-[18px] h-[18px]">
            <use xlink:href="#custom-moon"></use>
          </svg>
          <span>Dark</span>
        </a>
        <a href="#!" class="dropdown-item" onclick="layout_change('light')">
          <svg class="pc-icon w-[18px] h-[18px]">
            <use xlink:href="#custom-sun-1"></use>
          </svg>
          <span>Light</span>
        </a>
        <a href="#!" class="dropdown-item" onclick="layout_change_default()">
          <svg class="pc-icon w-[18px] h-[18px]">
            <use xlink:href="#custom-setting-2"></use>
          </svg>
          <span>Default</span>
        </a>
      </div>
    </li>
    <li class="dropdown pc-h-item">
      <a
        class="pc-head-link dropdown-toggle me-0"
        data-pc-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <svg class="pc-icon">
          <use xlink:href="#custom-setting-2"></use>
        </svg>
      </a>
      <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
        <a href="#!" class="dropdown-item">
          <i class="ti ti-user"></i>
          <span>My Account</span>
        </a>
        <a href="<?php echo get_home_url() . '/dashboard/pg-settings/';?>" class="dropdown-item">
          <i class="ti ti-settings"></i>
          <span>Settings</span>
        </a>
        <a href="#!" class="dropdown-item">
          <i class="ti ti-headset"></i>
          <span>Support</span>
        </a>
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="dropdown-item">
          <i class="ti ti-power"></i>
          <span>Logout</span>
        </a>
      </div>
    </li>
    <li class="dropdown pc-h-item">
      <a
        class="pc-head-link dropdown-toggle me-0"
        data-pc-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <svg class="pc-icon">
          <use xlink:href="#custom-notification"></use>
        </svg>
        <span class="badge bg-success-500 text-white rounded-full z-10 absolute right-0 top-0">3</span>
      </a>
      <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown p-2">
        <div class="dropdown-header flex items-center justify-between py-4 px-5">
          <h5 class="m-0">Notifications</h5>
          <a href="#!" class="btn btn-link btn-sm">Mark all read</a>
        </div>
        <div class="dropdown-body header-notification-scroll relative py-4 px-5" style="max-height: calc(100vh - 215px)">
          <p class="text-span mb-3">Today</p>
          <div class="card mb-2">
            <div class="card-body">
              <div class="flex gap-4">
                <div class="shrink-0">
                  <svg class="pc-icon text-primary w-[22px] h-[22px]">
                    <use xlink:href="#custom-layer"></use>
                  </svg>
                </div>
                <div class="grow">
                  <span class="float-end text-sm text-muted">2 min ago</span>
                  <h5 class="text-body mb-2">UI/UX Design</h5>
                  <p class="mb-0">
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                    type and scrambled it to make a type
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-2">
            <div class="card-body">
              <div class="flex gap-4">
                <div class="shrink-0">
                  <svg class="pc-icon text-primary w-[22px] h-[22px]">
                    <use xlink:href="#custom-sms"></use>
                  </svg>
                </div>
                <div class="grow">
                  <span class="float-end text-sm text-muted">1 hour ago</span>
                  <h5 class="text-body mb-2">Message</h5>
                  <p class="mb-0">Lorem Ipsum has been the industry's standard dummy text ever since the 1500.</p>
                </div>
              </div>
            </div>
          </div>
          <p class="text-span mb-3 mt-4">Yesterday</p>
          <div class="card mb-2">
            <div class="card-body">
              <div class="flex gap-4">
                <div class="shrink-0">
                  <svg class="pc-icon text-primary w-[22px] h-[22px]">
                    <use xlink:href="#custom-document-text"></use>
                  </svg>
                </div>
                <div class="grow ms-3">
                  <span class="float-end text-sm text-muted">2 hour ago</span>
                  <h5 class="text-body mb-2">Forms</h5>
                  <p class="mb-0">
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                    type and scrambled it to make a type
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-2">
            <div class="card-body">
              <div class="flex gap-4">
                <div class="shrink-0">
                  <svg class="pc-icon text-primary w-[22px] h-[22px]">
                    <use xlink:href="#custom-user-bold"></use>
                  </svg>
                </div>
                <div class="grow ms-3">
                  <span class="float-end text-sm text-muted">12 hour ago</span>
                  <h5 class="text-body mb-2">Challenge invitation</h5>
                  <p class="mb-2">
                    <span class="text-dark">Jonny aber</span>
                    invites to join the challenge
                  </p>
                  <button class="btn btn-sm btn-outline-secondary me-2">Decline</button>
                  <button class="btn btn-sm btn-primary">Accept</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-2">
            <div class="card-body">
              <div class="flex gap-4">
                <div class="shrink-0">
                  <svg class="pc-icon text-primary w-[22px] h-[22px]">
                    <use xlink:href="#custom-security-safe"></use>
                  </svg>
                </div>
                <div class="grow ms-3">
                  <span class="float-end text-sm text-muted">5 hour ago</span>
                  <h5 class="text-body mb-2">Security</h5>
                  <p class="mb-0">
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                    type and scrambled it to make a type
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center py-2">
          <a href="#!" class="text-danger-500 hover:text-danger-600 focus:text-danger-600 active:text-danger-600">
            Clear all Notifications
          </a>
        </div>
      </div>
    </li>
    <li class="dropdown pc-h-item header-user-profile">
      <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-pc-auto-close="outside" aria-expanded="false">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="user-image" class="user-avtar w-10 h-10 rounded-full" />
      </a>
      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2">
        <div class="dropdown-header flex items-center justify-between py-4 px-5">
          <h5 class="m-0">Profile</h5>
        </div>
        <div class="dropdown-body py-4 px-5">
          <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
            <div class="flex mb-1 items-center">
              <div class="shrink-0">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="user-image" class="w-10 rounded-full" />
              </div>
              <div class="grow ms-3">
                <h6 class="mb-1"><?php echo esc_html($user_name);?> 🖖</h6>
                <span><?php echo esc_html($user_email);?></span>
              </div>
            </div>
            <hr class="border-secondary-500/10 my-4" />
            <p class="text-span mb-3">Manage</p>
            <a href="<?php echo get_home_url() . '/dashboard/pg-settings/';?>" class="dropdown-item">
              <span>
                <svg class="pc-icon text-muted me-2 inline-block">
                  <use xlink:href="#custom-setting-outline"></use>
                </svg>
                <span>Settings</span>
              </span>
            </a>
            <a href="#" class="dropdown-item">
              <span>
                <svg class="pc-icon text-muted me-2 inline-block">
                  <use xlink:href="#custom-lock-outline"></use>
                </svg>
                <span>Change Password</span>
              </span>
            </a>
            <hr class="border-secondary-500/10 my-4" />

            <div class="grid mb-3">

              <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn btn-primary flex items-center justify-center">
                <svg class="pc-icon me-2 w-[22px] h-[22px]">
                  <use xlink:href="#custom-logout-1-outline"></use>
                </svg>
                Logout
            </a>
            
            </div>
          </div>
        </div>
      </div>
    </li>
    <!-- زر التحويل بين اللغات -->

  </ul>
</div>
</div>
</header>
<div class="offcanvas pc-announcement-offcanvas offcanvas-end" tcabindex="-1" id="announcement" aria-labelledby="announcementLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="announcementLabel">What's new announcement?</h5>
    <button
      data-pc-dismiss="#announcement"
      class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500"
    >
      <i class="ti ti-x"></i>
    </button>
  </div>
</div>
<!-- [ Header ] end -->