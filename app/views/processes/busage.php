<?php require APPROOT . '/views/inc/header.php'; ?>
    <?php echo flash('load_block_failed')?>

    <a href="<?php echo URLROOT; ?>/processes/show/<?php echo $data['process']->process_snapshot_id;?>" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['process']->process_name; ?></h1>
            <p class="lead"><?php echo $data['process']->process_snapshot_id; ?></p>
            <h4 class="display-4" id="number_of_blocks">Total : <?php echo sizeof($data['allBlocks']); ?> blocks</h4>
            <h4 class="display-4" id="number_of_blocks">In Use: <?php echo sizeof($data['blockPool']); ?> blocks</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <input id="search_bar" name = "search_result" class="form-control form-control-lg mr-2 " type="text" placeholder="Search" aria-label="Search">
        </div>

    </div> 
    <hr>

    <div id="sort_zone">
        <div class="row">
            <div class="col-md-6">
                <h5 class='text-center border p-3'id="setbo-count">All Blocks : <?php echo sizeof($data['allBlocks']); ?></h5>
                <?php foreach($data['allBlocks'] as $block) : ?>
                    <div class=" card card-body mb-3">
                    <div class="card-title">
                    <?php echo $block->name; ?>
                    <br>
                    <?php echo $block->id; ?>
                    </div>
                    </div>
                <?php endforeach ; ?>
            </div>
            
            <div class="col-md-6">
                <h5 class='text-center border p-3'id="setbo-count">Not In Use Blocks : <?php echo sizeof($data['notInUse']); ?></h5>
                <?php foreach($data['notInUse'] as $block) : ?>
                    <div class=" card card-body mb-3">
                    <div class="card-title">
                    <?php echo $block->name; ?>
                    <br>
                    <?php echo $block->id; ?>
                    </div>
                    </div>
                <?php endforeach ; ?>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>