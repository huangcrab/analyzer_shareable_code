<?php
  class Processes extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
          redirect('users/login');
        }
        $this->processModel = $this->model('Process');
        $this->blockModel = $this->model('Block');
    }

    public function index(){
      $processes = $this->processModel->getProcesses();
      $data = [
          'title' => 'Process Analyzer',
          'description' => 'Process uploaded will be displayed below.',
          'processes' => $processes,
      ];
      $this->view('processes/index', $data);

    }
    function compare_objects($obj_a, $obj_b) {
      return $obj_a->id - $obj_b->id;
    }
    public function busage($snapshot_id){
      $process = $this->processModel->getProcessBySnapId($snapshot_id);
      
      if($this->processModel->findProcessBySnapId($snapshot_id)){
        $blocks = $this->processModel->loadFromJSON($snapshot_id);
        $blockPool = array();
        $allBlocks = array();
        
        foreach($blocks as $block){
          $originalItem = new stdClass();
          $originalItem->name = $block['name'];
          $originalItem->id = $block['id'];
          $allBlocks[] = $originalItem;
          if(sizeof($block['items']['blocks'])>0){
            foreach($block['items']['blocks'] as $item){          
              if(isset($blockPool[$item['processId']])){
                //$newItem->count ++;
              }else{
                $newItem = new stdClass();
                
                $newItem->id = $item['processId'];
                $newItem->name = $item['name'];
                //$newItem->count = 1;

                $blockPool[$item['processId']] = $newItem;
              }
            }
          }
        }

        $notInUse = array_udiff($allBlocks, $blockPool, function ($obj_a, $obj_b) {
          return strcmp($obj_a->id ,$obj_b->id);
        });

        $data = [
          'process' => $process,
          'blockPool' => $blockPool,
          'allBlocks' => $allBlocks,
          'notInUse' => $notInUse
        ];
        
        $this->view('processes/busage', $data);
      } else {
        echo 'Process can not be found';
      }
    }
    public function analyze($snapshot_id){
      $process = $this->processModel->getProcessBySnapId($snapshot_id);
      if($this->processModel->findProcessBySnapId($snapshot_id) && $this->processModel->findProcessDetailsBySnapId($snapshot_id)){
        $blocks = $this->processModel->findBlocksFromFile();
        $items = array();
        foreach($blocks as $block){
          //Avoid Deployment Service
          if($block['id'] !=  "1.2222f109-5129-4379-a353-e61e6de86d03"){
            $newItem = $this->blockModel->getItemsFromFile($snapshot_id, $block['id'],'RETURN_AS_OBJECT');
            if($newItem != null){
              $items[] = $newItem;
            }
          }
        }
        $json = [
          'process' => $process,
          'items' => $items
        ];
        $jsonProcess = $process;
        $jsonItems = array_chunk($items, ceil(count($items) / 2));

        $fp = fopen('.\\process\\'.$snapshot_id.'-process.json', 'w');
        fwrite($fp, json_encode($jsonProcess));
        fclose($fp);
        foreach($jsonItems as $index=>$jsonItem){
          $fp = fopen('.\\process\\'.$snapshot_id.'-'.$index.'.json', 'w');
          fwrite($fp, json_encode($jsonItem));
          fclose($fp);
        }
        redirect('processes/index');
      } else {
        echo 'Process can not be found';
      }

    }

    public function errorLog($snapshot_id){
      if(file_exists(".\\process\\".$snapshot_id.'.json')){
        $errBlocks = array();
        if(file_exists(".\\process\\".$snapshot_id.'.log')){

        }else{
          
          $string = file_get_contents('.\\process\\'.$snapId.'-process.json');
                $processItem = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapId.'-0.json');
                $item0 = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapId.'-1.json');
                $item1 = json_decode($string, true);

                $process = [
                    'process' => $processItem,
                    'items' => array_merge($item0, $item1)
                ];
          if($process['process']['process_snapshot_id'] === $snapshot_id){
            $items = $process['items'];
            foreach($items as $item){
              $gateways[] = $item['items']['gateways'];
              foreach($gateways as $gateway){
                if($gateway === ''){
                  $errBlocks[] = [
                    'error' => 'Empty Gatway',
                    'block' => $item
                  ];
                }
              }
            }

          } else {
            flash('process_message','Date File Corrupted...');
            redirect('processes/index');
          }
        }

      }else{
        flash('process_message','Please analyzer the process first...');
        redirect('processes/index');
      }

    }
    public function show($snapshot_id){
      $process = $this->processModel->getProcessBySnapId($snapshot_id);
      if($this->processModel->findProcessBySnapId($snapshot_id)){
        $blocks = $this->processModel->loadFromJSON($snapshot_id);
        $data = [
          'process' => $process,
          'blocks' => $blocks
        ];
        
        $this->view('processes/show', $data);
      } else {
        echo 'Process can not be found';
      }

    }

    public function reset($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

          $process = $this->processModel->getProcessBySnapId($id);
          
          if(file_exists(".\\process\\".$id.'-process.json')){
              $mask = ".\\process\\".$process->process_snapshot_id.'*.json';
              //unlink(".\\process\\".$process->process_snapshot_id.'*.json');
              array_map('unlink', glob($mask));

              flash('process_message', 'Log Removed');
              redirect('processes/index');
          } else {
              flash('process_message', 'Log File not Exist');
              redirect('processes/index');
          }
      } else {
          redirect('processes/index');
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

          $process = $this->processModel->getProcessById($id);
          //check for owner
          /*if($process->user_id != $_SESSION['user_id']){
              redirect('');
          }*/
          $mask = ".\\process\\".$process->process_snapshot_id.'*.json';
          //unlink(".\\process\\".$process->process_snapshot_id.'*.json');
          array_map('unlink', glob($mask));
          if($this->processModel->deleteProcess($id)){
              flash('process_message', 'Process Removed');
              redirect('processes/index');
          } else {
              die('Something went wrong');
          }
      } else {
          redirect('processes/index');
      }
  }

    public function upload(){
      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = [
          'file_name' => '',
          'file_size' => '',
          'file_err' => ''
        ];

        if(!empty($_FILES['zip']['name'])){
          $zip = new ZipArchive();
          $temp = explode('.', $_FILES['zip']['name']);
          if(strtolower(end($temp)) !== 'twx'){
            $data['file_err'] = 'Please use a twx file';
          }
          if($_FILES['zip']['size']> 50943040){
            //in bytes
            $data['file_err'] = 'The file is too large';
          }
          if($zip->open($_FILES['zip']['tmp_name']) === false){
            $data['file_err'] = 'The twx fdile is invalid';
          }
        } else {
          $data['file_err'] = 'No file was picked';
          $this->view('processes/upload',$data);
        }
        if(empty($data['file_err'])){
          //upload
          $zip->extractTo('./process/');
          $zip->close();
          $_SESSION['file_size'] = number_format((float)($_FILES['zip']['size'] / 1048576), 2, '.', '');
          $this->processModel->setSize($_SESSION['file_size']);
          unset($_SESSION['file_size']);

          if($this->processModel->saveFromFile()){
            flash('upload_message','Upload Successfull');
            redirect('processes');
          } else {
            die('Import Failed');
            $this->view('processes/upload',$data);
          }
        } else {
          $this->view('processes/upload',$data);
        }

      } else {
        $data = [
          'file_err' => '',
        ];
        $this->view('processes/upload',$data);
      }
    }

  }