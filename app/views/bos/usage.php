<?php require APPROOT . '/views/inc/header.php'; ?>

    <?php echo flash('load_block_failed')?>
    
    <a href="<?php echo URLROOT; ?>/bos/show/<?php echo $data['process'];?>" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
    
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h3><?php echo $data['processName']; ?></h3>
            <h4><?php echo $data['process']; ?></h4>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <input id="search_bar" name = "search_result" class="form-control form-control-lg mr-2 " type="text" placeholder="Search" aria-label="Search">
        </div>
    </div> 
    
    <div id="sort_zone">
        
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>