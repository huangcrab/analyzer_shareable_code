<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT;?>/account/user_management" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create An Pending User</h2>
                <p>Please fill out the email of the user you want to add</p>
                <form action="<?php echo URLROOT;?>/account/add_pending_user" method="post">
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err']?></span>                    
                    </div>
                    <div class="form-group">
                        <label for="confirm_email">Confirm Email: <sup>*</sup></label>
                        <input type="email" name="confirm_email" class="form-control form-control-lg <?php echo (!empty($data['confirm_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_email']; ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_email_err']?></span>                    
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Add" class="btn btn-success btn-block">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>