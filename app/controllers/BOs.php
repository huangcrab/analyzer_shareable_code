<?php
  class BOs extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
          redirect('users/login');
        }
        $this->boModel = $this->model('BO');
        $this->processModel = $this->model('Process');
    }


    public function show($snapshotId){
        if($data = $this->boModel->getBOsFromJSON($snapshotId)){
            $data['processName'] = $this->processModel->getProcessBySnapId($snapshotId)->process_name;
            $this->view('bos/show', $data);
        }else{
            flash('process_message', 'JSON File is missing for snapshot: '.$snapshotId, 'alert alert-danger');
            redirect('processes/index');
        };
        
    }

    public function usage($snapshotId){

        if($data = $this->boModel->getBOsFromJSON($snapshotId)){
            $data['processName'] = $this->processModel->getProcessBySnapId($snapshotId)->process_name;
            $this->view('bos/usage', $data);
        }else{
            flash('process_message', 'JSON File is missing for snapshot: '.$snapshotId, 'alert alert-danger');
            redirect('processes/index');
        };
        
    }
}