<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row mb-0">
        <div class="col-md-5 mb-0">
            <div class="container text-left mb-0 pt-2 <?php echo $data['color']==='green' ? 'bg-success' : 'bg-warning';?>"><h4 class="mb-0 text-white"><?php echo $data['snapshotName']; ?></div></h4>
        </div>
    </div>
    
    <div style="border: 10px solid black" class="jumbotron jumbotron-fluid text-center <?php echo $data['color']==='green' ? 'border-success' : 'border-warning';?>">
        <div class="row">
            <div class="col-md-10 container mb-2">
                <h1 class="display-3"><?php echo $data['name']; ?></h1>
                <p class="lead"><strong>ID: </strong><?php echo $data['id']; ?></p>
                <a class="btn btn-outline-info btn-lg" target="_blank" href="https://guesswhat.ca/#/app/2066.663df20f-f401-479f-9639-57e28e349386/snapshot/<?php echo $data['snapshotId']; ?>/process/<?php echo $data['id'];?>">Content AdminUI</a>
            </div>
            <div class="container col-md-2">
                <h3>Exit# <?php echo sizeof($data['exits'])?></h3>
                <?php foreach($data['exits'] as $exit) : ?>
                    <div class="bg-grey">    
                        <div class="btn btn-danger btn-block mb-1"><?php echo $exit['name']?></div>
                    </div>
                <?php endforeach ; ?>
            
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-12">
                <input id="search_bar" name = "search_result" class="form-control form-control-lg mr-2 " type="text" placeholder="Search" aria-label="Search">
            </div>
        </div> 
    <hr>
    <?php foreach($data['processes'] as $process) : ?>
    <div <?php echo hasErrors($data['errors'],$process['name']) ? 'style="border: 10px solid black"' : '';?> class="bg-success text-white card card-body mb-3 <?php echo hasErrors($data['errors'],$process['name']) ? 'border-danger' : '';?>" >
            <div class="row">
                <div class="col-md-10">
                    <h3 class="card-title text-center"><strong>Process:</strong> <?php echo $process['name']; ?></h3>
                    <p class="card-text text-center"><strong>Id:</strong> <?php echo $process['processId']; ?></p> 
                    <p class="card-text text-center"><strong>color:</strong> <?php echo $process['color']; ?></p>
                    <p class="card-text text-center"><strong>stepType:</strong> <?php echo $process['type']; ?></p>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo URLROOT;?>/blocks/show/<?php echo $data['snapshotId']; ?>/<?php echo $process['processId']; ?>" class="btn btn-primary btn-lg btn-block">Detail</a>
                </div>
            </div>
            <hr>
        <div class="row">
            <div class="col-md-6">
                <?php foreach($process['mapping']['input'] as $input) : ?>
                    <strong><?php echo $input['name']; ?>: </strong><p class="card-text border <?php echo hasErrors($data['errors'],$process['name']) && mapError($data['errors'],$input['name']) ? 'bg-danger' : '';?>"> <?php echo $input['value']; ?></p>
                <?php endforeach ; ?>
            </div>
            
            <div class="col-md-6">
                <?php foreach($process['mapping']['output'] as $output) : ?>
                    <strong><?php echo $output['name']; ?>: </strong><p class="card-text border"> <?php echo $output['value']; ?></p>
                <?php endforeach ; ?>
            </div>
        </div>
    </div>
    <?php endforeach ; ?>

    <?php foreach($data['items'] as $item) : ?>
    <div class="bg-secondary text-white card card-body mb-3 <?php echo hasErrors($data['errors'],$item['name']) ? 'border-danger' : '';?>" <?php echo hasErrors($data['errors'],$item['name']) ? 'style="border: 10px solid black"' : '';?>>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="card-title text-center"><strong>Step Name:</strong> <?php echo $item['name']; ?></h3>
                    <p class="card-text text-center"><strong>Step Type:</strong> <?php echo $item['stepType']; ?></p> 
                </div>
            </div>
            <hr>
        <div class="row">
            <div class="col-md-6">
                <?php foreach($item['mapping']['input'] as $input) : ?>
                    <strong><?php echo $input['name']; ?>: </strong><p class="card-text border <?php echo hasErrors($data['errors'],$input['name']) && mapError($data['errors'],$input['name']) ? 'bg-danger' : '';?>"> <?php echo $input['value']; ?></p>
                <?php endforeach ; ?>
            </div>
            
            <div class="col-md-6">
                <?php foreach($item['mapping']['output'] as $output) : ?>
                    <strong><?php echo $output['name']; ?>: </strong><p class="card-text border"> <?php echo $output['value']; ?></p>
                <?php endforeach ; ?>
            </div>
        </div>
    </div>
    <?php endforeach ; ?>

    <?php foreach($data['others'] as $other) : ?>
    <div class="bg-dark text-white card card-body mb-3 <?php echo hasErrors($data['errors'],$other['name']) ? 'border-danger' : '';?>" <?php echo hasErrors($data['errors'],$other['name']) ? 'style="border: 10px solid black"' : '';?>>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="card-title text-center"><strong>Step Name:</strong> <?php echo $other['name']; ?></h3>
                    <p class="card-text text-center"><strong>Step Type:</strong> <?php echo $other['type']; ?></p> 
                </div>
            </div>
            <hr>
        <div class="row">
            <div class="col-md-6">
                <?php foreach($item['mapping']['input'] as $input) : ?>
                    <strong><?php echo $input['name']; ?>: </strong><p class="card-text border <?php echo hasErrors($data['errors'],$input['name']) && mapError($data['errors'],$input['name']) ? 'bg-danger' : '';?>"> <?php echo $input['value']; ?></p>
                <?php endforeach ; ?>
            </div>
            
            <div class="col-md-6">
                <?php foreach($item['mapping']['output'] as $output) : ?>
                    <strong><?php echo $output['name']; ?>: </strong><p class="card-text border"> <?php echo $output['value']; ?></p>
                <?php endforeach ; ?>
            </div>
        </div>
    </div>
    <?php endforeach ; ?>
    

    <?php foreach($data['bos'] as $bo) : ?>
    <div <?php echo hasErrors($data['errors'],$bo['name']) ? 'style="border: 10px solid black"' : '';?> class="bg-warning text-dark card card-body mb-3 <?php echo hasErrors($data['errors'],$bo['name']) ? 'border-danger' : '';?>">
        <div class="col-md-12">
            <h3 class="card-title text-center"><strong>BO Step:</strong> <?php echo $bo['name']; ?></h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php foreach($bo['mapping']['input'] as $input) : ?>
                    <strong><?php echo $input['name']; ?>: </strong><p class="card-text border <?php echo hasErrors($data['errors'],$input['name']) && mapError($data['errors'],$input['name']) ? 'bg-danger' : '';?>"> <?php echo $input['value']; ?></p>
                <?php endforeach ; ?>
            </div>
            
            <div class="col-md-6">
                <?php foreach($bo['mapping']['output'] as $output) : ?>
                    <strong><?php echo $output['name']; ?>: </strong><p class="card-text border"> <?php echo $output['value']; ?></p>
                <?php endforeach ; ?>
            </div>
        </div>
    </div>
    <?php endforeach ; ?>
    
    <?php foreach($data['scripts'] as $script) : ?>
        <div class="bg-dark text-white card card-body mb-3">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="card-title text-center"><strong>Script:</strong> <?php echo $script['name']; ?></h3>
                    <p class="card-text"><pre class="pre-scrollable text-white"><?php echo $script['value']; ?></pre></p>
                </div>
            </div>
        </div>
    <?php endforeach ; ?>


    <?php foreach($data['gateways'] as $gateway) : ?>
    <div <?php echo hasErrors($data['errors'],$gateway['name']) ? 'style="border: 10px solid black"' : '';?> class="bg-light card card-body mb-3 <?php echo hasErrors($data['errors'],$gateway['name']) ? 'border-danger' : '';?>">
        <div class="row">
            <div class="col-md-10">
                <h3 class="card-title text-center"><strong>Gateway: </strong><?php echo $gateway['name']; ?></h3>
                <?php foreach($gateway['conditions'] as $condition) : ?>
                    <p class="card-text">Condition <?php echo $condition['seq']; ?>:  <?php echo $condition['value']; ?></p>
                <?php endforeach ; ?> 
            </div>
        </div>
    </div>
    <?php endforeach ; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>