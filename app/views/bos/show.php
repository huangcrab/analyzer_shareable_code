<?php require APPROOT . '/views/inc/header.php'; ?>

    <?php echo flash('load_block_failed')?>
    <a href="<?php echo URLROOT; ?>/processes/show/<?php echo $data['process'];?>" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
    
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h3><?php echo $data['processName']; ?></h3>
            <h4><?php echo $data['process']; ?></h4>
            <a href="<?php echo URLROOT;?>/bos/usage/<?php echo $data['process']; ?>" class="btn btn-orange btn-lg">BO Usage</a>

        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <input id="search_bar" name = "search_result" class="form-control form-control-lg mr-2 " type="text" placeholder="Search" aria-label="Search">
        </div>
    </div> 
    
    <div id="sort_zone">
        <div class="row">
            <div class="col-md-6 setbo-col">
                <h5 class='text-center border p-3'id="setbo-count">SET BO : <?php echo sizeof($data['setBo']); ?> places</h5>
                <?php foreach($data['setBo'] as $bo) : ?>
                    <?php if($bo->action === 'set'):?>
                            <div class=" card card-body mb-3 bg-orange setbo-card">
                            <div class="card-title"><h6><?php echo $bo->name; ?></h6><br><p><?php echo $bo->value; ?> <br><?php echo $bo->block; ?></p></div>
                            </div>
                    <?php endif;?>
                <?php endforeach ; ?>
            </div>

            <div class="col-md-6 checkbo-col">
                <h5 class='text-center border p-3'id="checkbo-count">CHECK BO : <?php echo sizeof($data['checkBo']); ?> places</h5>
                <?php foreach($data['checkBo'] as $bo) : ?>
                            <?php if($bo->action === 'check'):?>
                            <div class=" card card-body mb-3 bg-purple checkbo-card">
                            <div class="card-title"><h6><?php echo $bo->name; ?></h6><br><p>Check If <br><?php echo $bo->block; ?></p></div>
                            </div>
                            <?php elseif($bo->action === 'gateway'):?>
                            <div class=" card card-body mb-3 bg-secondary">
                            <div class="card-title"><h6><?php echo $bo->name; ?></h6><br><p><?php echo $bo->value; ?> <br><?php echo $bo->block; ?></p></div>
                            </div>
                            <?php elseif($bo->action === 'error'):?>
                            <div class=" card card-body mb-3 bg-danger">
                            <div class="card-title"><h6>Syntax Error</h6><br><h6><?php echo $bo->name; ?></h6><br><p><?php echo $bo->value; ?> <br><?php echo $bo->block; ?></p></div>
                            </div>
                            <?php endif;?>
                <?php endforeach ; ?>
            </div>
        </div>
        
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>