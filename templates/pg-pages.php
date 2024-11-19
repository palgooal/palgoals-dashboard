<?php
// تأكد من منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// تضمين الهيدر
include plugin_dir_path(__DIR__) . 'templates/partials/header.php';

// إنشاء استعلام لجلب جميع الصفحات
$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
$args = array(
    'post_type' => 'page',
    'posts_per_page' => 10, // إحضار الصفحات
    'paged' => $paged,
    'post_status' => array('publish', 'draft') // جلب الصفحات المنشورة والمسودات
);
$query = new WP_Query($args);

?>
<!-- [ Main Content ] start -->
<div class="pc-container">
  <div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="page-header-title">
          <h2 class="mb-0"><?php _e('Pages List', 'palgoals-dash');?></h2>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
  <!-- [ Main Content ] start -->
  <div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12">
      <div class="card table-card">
        <div class="card-header">
          <div class="sm:flex items-center justify-between">
            <h5 class="mb-3 sm:mb-0"><?php _e('All Pages', 'palgoals-dash');?></h5>
          <div>
            <button data-pc-animate="side-fall" type="button" class="btn btn-primary" data-pc-toggle="modal" data-pc-target="#animateModal">
              <?php _e('Add Pages', 'palgoals-dash');?>
            </button>
          </div>

        </div>
      </div>
      <div class="card-body pt-3">
        <div class="table-responsive">
          <table class="table table-hover" id="pc-dt-simple">
            <thead>
              <tr>
                <th><input type="checkbox" id="select_all"></th>
                <th><?php _e('Title Pages', 'palgoals-dash');?></th>
                <th><?php _e('Publish Date', 'palgoals-dash');?></th>
                <th><?php _e('Status', 'palgoals-dash');?></th>
                <th><?php _e('Action', 'palgoals-dash');?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              if($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                $current_status = get_post_status(get_the_ID()); ?>
                <tr>
                  <td><input type="checkbox" name="page_ids[]" value="<?php echo get_the_ID(); ?>" class="select_single"></td>
                  <td><?php the_title(); ?></td>
                  <td><?php echo get_the_date(); ?></td>
                  <td>
    <?php
    $current_status = get_post_status(); // الحصول على الحالة الحالية
    ?>
    <div class="flex space-x-2">
        <button class="toggle-status <?php echo ($current_status === 'publish') ? 'bg-green-500' : 'bg-gray-300'; ?>" data-page-id="<?php echo get_the_ID(); ?>">
            <?php _e('Publish', 'palgoals-dash'); ?>
        </button>
        <button class="toggle-status <?php echo ($current_status === 'draft') ? 'bg-green-500' : 'bg-gray-300'; ?>" data-page-id="<?php echo get_the_ID(); ?>">
            <?php _e('Draft', 'palgoals-dash'); ?>
        </button>
    </div>
</td>


                  <td>
                    <a href="<?php echo get_permalink();?>" target="_blank" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                      <i class="ti ti-eye text-xl leading-none"></i>
                    </a>
                    <a href="<?php echo admin_url('post.php?post=' . get_the_ID() . '&action=elementor'); ?>" target="_blank" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                      <i class="ti ti-edit text-xl leading-none"></i>
                    </a>
                    <a href="#" class="delete-page w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" data-page-id="<?php echo get_the_ID(); ?>">
                      <i class="ti ti-trash text-xl leading-none"></i>
                    </a>
                  </td>
                </tr>
                <?php endwhile;?>
                <?php else : ?>
                  <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-600"><?php _e('No pages found.', 'palgoals-core'); ?></td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
            </div>
              
              <!-- Pagination -->
            <?php
            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
              $current_page = max(1, get_query_var('paged'));
            ?>
            <nav aria-label="Page navigation example">
              <ul class="flex justify-center *:*:inline-block *:*:px-3 *:*:py-1.5 *:border *:border-theme-border *:dark:border-themedark-border hover:*:bg-secondary-300/10 mb-3">
                <?php if($paged > 1): ?>
                  <li class="ltr:rounded-l-lg rtl:rounded-r-lg"><a href="<?php echo add_query_arg('paged', $paged - 1);?>"><?php _e('Previous', 'palgoals-dash');?></a></li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $total_pages; $i++): ?> 
                  <li><a href="<?php echo add_query_arg('paged', $i); ?>"><?php echo $i; ?></a></li>
                <?php endfor;?>
                <?php if($paged < $total_pages): ?>
                  <li class="ltr:rounded-r-lg rtl:rounded-l-lg"><a href="<?php echo add_query_arg('paged', $paged + 1);?>"><?php _e('Next', 'palgoals-dash');?></a></li>
                <?php endif; ?>
              </ul>
            </nav>
            <?php } ?>
        </div>
      </div>
      </div>
    <!-- [ Main Content ] end -->
  </div>
  </div>
    <!-- [ Main Content ] end -->
<?php include plugin_dir_path(__DIR__). 'templates/modal/modal-add-pages.php';?>    
<input type="hidden" id="elementor-edit-link" value=""/>
<?php include plugin_dir_path(__DIR__). 'templates/partials/footer.php';?>
