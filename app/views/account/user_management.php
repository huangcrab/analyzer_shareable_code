<?php require APPROOT . '/views/inc/header.php'; ?>
<?php echo flash('redirect_success');?>
    <div class="row">
        <div class="col-md-6">
            <h2>Active Users: </h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT;?>/account/add_pending_user" class="btn btn-primary pull-right">
                <i class="fa fa-pencil"></i> Add Pending User
            </a>
        </div>
    </div>
    <?php foreach($data['users'] as $user) : ?>
        <div class="card card-body mb-3">
            <h4 class="card-title"><?php echo $user->email; ?>: <?php echo $user->name; ?> 
            <?php if($data['power']): ?>
            <form class="card-title pull-right" action="<?php echo URLROOT;?>/account/delete_user/<?php echo $user->id;?>" method="post">
                <input type="submit" value="Delete" class="btn btn-danger">
            </form>
            <?php endif; ?>
            </h4>
        </div>

    <?php endforeach ; ?>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h2>Pending Users: </h2>
        </div>
    </div>
    <?php foreach($data['pendingUsers'] as $user) : ?>
        <div class="card card-body mb-3">
            <h4 class="card-title"><?php echo $user->email; ?>
            </h4>
        </div>
    <?php endforeach ; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>