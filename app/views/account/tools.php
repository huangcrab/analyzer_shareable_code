<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <div class="row">
                    <a href="<?php echo URLROOT; ?>/account/user_management" class="btn btn-primary btn-block">User Management</a>
                </div>
                <hr>
                <div class="row">
                    <a href="<?php echo URLROOT; ?>/account/update_password" class="btn btn-danger btn-block">Change Password</a>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>