<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><?php esc_html_e('Add Categories', 'palgoals-dash'); ?></h5>
        <button data-pc-dismiss="#offcanvasExample" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
            <i class="ti ti-x"></i>
        </button>
    </div>
    <div class="offcanvas-body customer-body">
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="email" class="form-control" placeholder="Enter full name" />
                <small class="form-text text-muted">Please enter your full name</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" placeholder="Enter email" />
                    <small class="form-text text-muted">Please enter your Email</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="enter Password" />
                  </div>
                  <div>
                    <label class="form-label">language:</label>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input input-primary" id="customCheckinl1" checked />
                      <label class="form-check-label" for="customCheckinl1">English</label>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input input-primary" id="customCheckinl2" />
                      <label class="form-check-label" for="customCheckinl2">French</label>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input input-primary" id="customCheckinl3" />
                      <label class="form-check-label" for="customCheckinl3">Dutch</label>
                    </div>
                  </div>
                </form>
              </div>
        <div class="text-end">
            <button class="btn btn-light-danger btn-sm" data-pc-dismiss="#offcanvasExample">Close</button>
        </div>
    </div>
</div>