<?php
  class Blocks extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
          redirect('users/login');
        }
        $this->blockModel = $this->model('Block');
        $this->processModel = $this->model('Process');
    }


    public function show($snapshotId,$blockId){
        
        if($data = $this->blockModel->getItemsFromJSON($snapshotId,$blockId)){
            $this->view('blocks/show', $data);
        }else{
            flash('load_block_failed', 'XML File is missing for block: '.$blockId, 'alert alert-danger');
            redirect('processes/show/'.$snapshotId);
        };
        
    }
}