<?php
  class Contents extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
        $this->contentModel = $this->model('Content');

    }
    public function index(){
        $data=[
            'title' => 'Content',
            'description' => 'load content'
        ];
       $this->view('content/index',$data);
        
    }

   

    public function load(){
        $data = $_POST['json'];
        $json = json_decode($data);

        if($json !== null){
          $result = $this->contentModel->load($json);
          if($result === true){
            $res = json_encode(array('status' => 'ok', 'data' =>sizeof($json).' is loaded' ));
          }else if($result !== false){
            $res = json_encode(array('status' => 'wrong', 'data' =>$result.'/'.sizeof($json).' is loaded' ));
          }else{
            $res = json_encode(array('status' => '400', 'err' =>$res ));
          } 
        }else{
          $res = json_encode(array('status' => '400', 'err' =>'empty payload' ));
        }

        echo $res;
    }

    public function upload(){
      
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          $data = [
            'file_name' => '',
            'file_size' => '',
            'file_err' => ''
          ];
  
          if(!empty($_FILES['upload']['name'])){
            $temp = explode('.', $_FILES['upload']['name']);
            if(strtolower(end($temp)) !== 'json'){
              $data['file_err'] = 'Please use a json file';
            }
            if($_FILES['upload']['size']> 50943040){
              //in bytes
              $data['file_err'] = 'The file is too large';
            }
          } else {
            $data['file_err'] = 'No file was picked';
            $this->view('content/upload',$data);
          }
          if(empty($data['file_err'])){
            //upload
            $uploadDirectory = getcwd()."/process/content/".basename($_FILES['upload']['name']);
            //$uploadPath = $currentDir . $uploadDirectory . basename($_FILES['upload']['name']); 
            $didUpload = move_uploaded_file($_FILES['upload']['tmp_name'], $uploadDirectory);
            $json = json_decode(file_get_contents($uploadDirectory));
            if($didUpload){
                if($this->contentModel->saveFromFile($json)){
                    flash('upload_message',$_FILES['upload']['name'].'- Upload Successfull');
                    redirect('contents');
                  } else {
                    die('Upload Failed');
                    $this->view('content/upload',$data);
                  }
            }else{
                $data['file_err'] = 'Upload Failed';
                $this->view('content/upload',$data);
            }
            
          } else {
            $this->view('content/upload',$data);
          }
  
        } else {
          $data = [
            'file_err' => '',
          ];
          $this->view('content/upload',$data);
        }
      }

    
}