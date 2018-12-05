<?php require APPROOT . '/views/inc/header.php'; ?>
    <?php flash('upload_message');flash('process_message')?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['title']; ?></h1>
            <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div>
    <?php foreach($data['processes'] as $process) : ?>
        <div class="card card-body mb-3">
            <h4 class="card-title"><?php echo $process->process_name; ?></h4>
            <div class="bg-light p-2 mb-3">
                Snapshot was created at <?php echo $process->process_created_at; ?> <strong>Size:</strong> <?php echo $process->process_size; ?> MB
            </div>
            <p class="card-text"><strong>Snapshot Id:</strong> <?php echo $process->process_snapshot_id?></p>
            <div class="row">
                <?php if(file_exists(".\\process\\".$process->process_snapshot_id.'-process.json')):?>
                    <div class="col-md-9">
                        <a href="<?php echo URLROOT; ?>/processes/show/<?php echo $process->process_snapshot_id;?>" class="btn btn-dark btn-block mb-1">Overview</a>
                    </div>
                <?php else:?>
                    <div class="col-md-9">
                        <a href="<?php echo URLROOT; ?>/processes/analyze/<?php echo $process->process_snapshot_id;?>" class="btn btn-success btn-block mb-1">Analyze</a>
                    </div>
                <?php endif;?>
                <div class="col-md-1">
                    <form action="<?php echo URLROOT;?>/processes/reset/<?php echo $process->process_snapshot_id;?>" method="post">
                        <input type="submit" value="Reset" class="btn btn-danger btn-block mb-1">
                    </form>  
                </div>
                <div class="col-md-2">
                    <form action="<?php echo URLROOT;?>/processes/delete/<?php echo $process->id;?>" method="post">
                        <input type="submit" value="Delete" class="btn btn-danger btn-block mb-1">
                    </form>  
                </div>
                            
            </div>
        </div>
        
    <?php endforeach ; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>
