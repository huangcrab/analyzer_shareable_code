<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Change Password</h2>
                <p>fill out the form to change password</p>
                <form action="<?php echo URLROOT;?>/users/update_password" method="post">
                    <div class="form-group">
                        <label for="old_password">Old Password: <sup>*</sup></label>
                        <input type="password" name="old_password" class="form-control form-control-lg <?php echo (!empty($data['old_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['old_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['old_password_err']?></span>                    
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password: <sup>*</sup></label>
                        <input type="password" name="new_password" class="form-control form-control-lg <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['new_password_err']?></span>                    
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']?></span>                    
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Confirm" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/processes/index" class="btn btn-danger btn-block">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>