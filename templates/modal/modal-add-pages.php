<div id="animateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="animateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add a new page to your site</h5>
                <button data-pc-modal-dismiss="#animateModal" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="page-title" class="form-label"><?php _e('Page Title', 'palgoals-dashe'); ?></label>
                        <input type="text" name="page_title" id="page-title" class="form-control" required placeholder="<?php _e('Enter the page name', 'palgoals-dashe'); ?>" />
                        <small class="form-text text-muted">Please enter the page name</small>
                    </div>
                    <div class="mb-3">
                        <label for="page-slug" class="form-label"><?php _e('Cute name Slug', 'palgoals-dashe'); ?></label>
                        <input type="text" name="page_slug" id="page-slug" class="form-control" required placeholder="<?php _e('Cute name Slug, preferably in English', 'palgoals-dashe'); ?>" />
                        <small class="form-text text-muted"><?php _e('The new link will be like this', 'palgoals-dashe'); ?> <?= get_site_url() . '/' ?><span class="new_slug">{slug}</span></small>
                    </div>
                    <input type="hidden" id="redirect-url" name="redirect_url" value="<?php echo esc_url(get_site_url()); ?>" />
                </div>
            </div>
            <div class="modal-footer gap-4">
                <button id="create-page" class="btn btn-primary ltr:ml-2 trl:mr-2"><?php _e('Add', 'palgoals-dashe'); ?></button>
                <button class="btn btn-secondary" data-pc-modal-dismiss="#animateModal"><?php _e('closing', 'palgoals-dashe'); ?></button>
            </div>
            </form>
        </div>
    </div>
</div>