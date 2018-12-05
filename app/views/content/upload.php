<?php require APPROOT . '/views/inc/header.php'; ?>
    <div>
      <h3>Only JSON File can be uploaded at this moment.</h3>
    </div>
    <hr>
    <form action="<?php echo URLROOT; ?>/contents/upload" method="post" enctype="multipart/form-data">
            <input type="file" name="upload" class="form-control form-control-lg <?php echo (!empty($data['file_err'])) ? 'is-invalid' : ''; ?>">

                <span class="invalid-feedback"><?php echo $data['file_err'];?></span>
                 
            <hr>
            <input type="submit" value="Upload" class="btn btn-success">
    </form>

<?php require APPROOT . '/views/inc/footer.php'; ?>

