<?php require APPROOT . '/views/inc/header.php'; ?>
    <?php echo flash('load_block_failed')?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['process']->process_name; ?></h1>
            <p class="lead"><?php echo $data['process']->process_snapshot_id; ?></p>
            <h4 class="display-4" id="number_of_blocks"><?php echo sizeof($data['blocks']); ?> blocks</h4>
        </div>
        <a href="<?php echo URLROOT;?>/bos/show/<?php echo $data['process']->process_snapshot_id; ?>" class="btn btn-orange btn-lg">Business Objects</a>
        <a href="#" class="btn btn-danger btn-lg">Error Log</a>
        <a href="<?php echo URLROOT;?>/processes/busage/<?php echo $data['process']->process_snapshot_id; ?>" class="btn btn-primary btn-lg">Block Usage</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <input id="search_bar" name = "search_result" class="form-control form-control-lg mr-2 " type="text" placeholder="Search" aria-label="Search">
        </div>

        <div class="col-md-4">
            <select id="sort_select" class="form-control form-control-lg bg-info text-white ml-0 pl-0">
                <option>Sort by ...</option>
                <option>Name</option>
                <option>Modified Date</option>
            </select>
        </div>
    </div> 
    <hr>
    <div class="row">
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box" type="checkbox" id="check-all" onclick="checkError(this)"> All Errors
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box sub-check" type="checkbox" id="check-eg" onclick="checkError(this)"> Empty Gateway
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box sub-check" type="checkbox" id="check-mm" onclick="checkError(this)"> Miss Mapping
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box sub-check" type="checkbox" id="check-ls" onclick="checkError(this)"> Long Signal
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box sub-check" type="checkbox" id="check-me" onclick="checkError(this)"> Miss EndLink
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn btn-danger btn-block" onclick="toggleCheck(this)">
                <input class="btn check-box sub-check" type="checkbox" id="check-hd" onclick="checkError(this)"> Has Default
            </div>
        </div>
    </div> 
    <hr>
    <div id="sort_zone">
        <?php foreach($data['blocks'] as $block) : ?>
        <?php //if($block != null): ?>
        <div class="card card-body mb-3 <?php echo $block['color']==='green' ? 'bg-success' : 'bg-warning';?> <?php echo $block['errors']['1']!=null ? 'error empty-gateway' : '';?> <?php echo $block['errors']['2']!=null ? 'error long-singal' : '';?> <?php echo $block['errors']['3']!=null ? 'error miss-mapping' : '';?> <?php echo $block['errors']['4']!=null ? 'error miss-endlink' : '';?> <?php echo $block['errors']['5']!=null ? 'error has-default' : '';?>">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="card-title"><?php echo $block['name']; ?></h3><?php if($block['errors']['1'] != null): ?><i class="fa fa-times text-danger"></i><?php endif; ?>
                    <p class="card-text"><strong>Id:</strong> <?php echo $block['id']; ?></p> 
                    <p class="card-text"><strong>Modified:</strong> <?php echo date('r',$block['modified']['time']/1000); ?></p> 
                </div>
                <div class="col-md-2">
                    <a href="<?php echo URLROOT;?>/blocks/show/<?php echo $data['process']->process_snapshot_id; ?>/<?php echo $block['id'];?>" class="btn btn-primary btn-lg btn-block">Detail</a>
                </div>
            </div>
        </div>
        <?php //endif; ?>
        <?php endforeach ; ?>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>